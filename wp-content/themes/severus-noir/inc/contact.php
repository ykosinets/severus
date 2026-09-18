<?php
/**
 * The contact form: storage, REST endpoint and notification.
 *
 * Replaces the form plugin the theme used to render. Everything here is core
 * WordPress — no jQuery on the page, and submissions stay in the database as a
 * private post type instead of a plugin's own tables.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

const SEVERUS_LEAD_TYPE = 'severus_lead';

/**
 * Somewhere for submissions to live, readable in the admin.
 */
function severus_register_leads(): void {
	register_post_type(
		SEVERUS_LEAD_TYPE,
		array(
			'labels'          => array(
				'name'          => __( 'Enquiries', 'severus-noir' ),
				'singular_name' => __( 'Enquiry', 'severus-noir' ),
				'menu_name'     => __( 'Enquiries', 'severus-noir' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 26,
			'supports'        => array( 'title', 'editor' ),
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
		)
	);
}
add_action( 'init', 'severus_register_leads' );

/**
 * Show who wrote in without opening the entry.
 */
function severus_lead_columns( array $columns ): array {
	return array(
		'cb'      => $columns['cb'] ?? '',
		'title'   => __( 'Name', 'severus-noir' ),
		'company' => __( 'Company', 'severus-noir' ),
		'email'   => __( 'Email', 'severus-noir' ),
		'date'    => $columns['date'] ?? __( 'Date', 'severus-noir' ),
	);
}
add_filter( 'manage_' . SEVERUS_LEAD_TYPE . '_posts_columns', 'severus_lead_columns' );

/**
 * Fill the columns above.
 */
function severus_lead_column( string $column, int $post_id ): void {
	if ( ! in_array( $column, array( 'company', 'email' ), true ) ) {
		return;
	}

	$value = (string) get_post_meta( $post_id, "severus_{$column}", true );

	echo 'email' === $column && $value
		? '<a href="mailto:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>'
		: esc_html( $value );
}
add_action( 'manage_' . SEVERUS_LEAD_TYPE . '_posts_custom_column', 'severus_lead_column', 10, 2 );

/**
 * The endpoint the form posts to.
 *
 * Left public: a page cache would serve a stale nonce and lock real people out,
 * and there is nothing to forge here. Spam is filtered by the honeypot, by how
 * fast the form came back, and by validation.
 */
function severus_register_contact_route(): void {
	register_rest_route(
		'severus/v1',
		'/contact',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'severus_handle_contact',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'severus_register_contact_route' );

/**
 * Validate, store and notify.
 */
function severus_handle_contact( WP_REST_Request $request ) {
	// A bot filled the hidden field, or the form came back faster than a person
	// could type. Answer as though it worked and drop it.
	$opened = (int) $request->get_param( 'opened' );

	if ( $request->get_param( 'website' ) || ( $opened && time() - $opened < 3 ) ) {
		return new WP_REST_Response( array( 'message' => severus_contact_thanks() ), 200 );
	}

	$fields = array(
		'name'    => sanitize_text_field( (string) $request->get_param( 'name' ) ),
		'company' => sanitize_text_field( (string) $request->get_param( 'company' ) ),
		'email'   => sanitize_email( (string) $request->get_param( 'email' ) ),
		'details' => sanitize_textarea_field( (string) $request->get_param( 'details' ) ),
	);

	// A malformed address survives sanitize_email() as an empty string, so the
	// address is judged on what was actually submitted.
	if ( ! is_email( $fields['email'] ) ) {
		return new WP_Error( 'severus_email', __( 'That email address does not look right.', 'severus-noir' ), array( 'status' => 422 ) );
	}

	foreach ( $fields as $value ) {
		if ( '' === trim( $value ) ) {
			return new WP_Error( 'severus_incomplete', __( 'Please fill in every field.', 'severus-noir' ), array( 'status' => 422 ) );
		}
	}

	$fields['details'] = mb_substr( $fields['details'], 0, 180 );

	$post_id = wp_insert_post(
		array(
			'post_type'    => SEVERUS_LEAD_TYPE,
			'post_status'  => 'private',
			'post_title'   => $fields['name'],
			'post_content' => $fields['details'],
			'meta_input'   => array(
				'severus_company' => $fields['company'],
				'severus_email'   => $fields['email'],
			),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'severus_store', __( 'We could not record that. Please email us instead.', 'severus-noir' ), array( 'status' => 500 ) );
	}

	severus_notify_contact( $fields );

	return new WP_REST_Response( array( 'message' => severus_contact_thanks() ), 201 );
}

/**
 * Email the enquiry on, replying straight to the sender.
 *
 * @param array<string,string> $fields Sanitised submission.
 */
function severus_notify_contact( array $fields ): void {
	$to = severus_contact_email();

	if ( ! is_email( $to ) ) {
		return;
	}

	$body = array(
		sprintf( /* translators: %s: sender name. */ __( 'Name: %s', 'severus-noir' ), $fields['name'] ),
		sprintf( /* translators: %s: company. */ __( 'Company: %s', 'severus-noir' ), $fields['company'] ),
		sprintf( /* translators: %s: email. */ __( 'Email: %s', 'severus-noir' ), $fields['email'] ),
		'',
		$fields['details'],
	);

	wp_mail(
		$to,
		sprintf(
			/* translators: 1: site name, 2: sender name. */
			__( '[%1$s] Enquiry from %2$s', 'severus-noir' ),
			get_bloginfo( 'name' ),
			$fields['name']
		),
		implode( "\n", $body ),
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $fields['name'] . ' <' . $fields['email'] . '>',
		)
	);
}

/**
 * What the form says once it has gone through.
 */
function severus_contact_thanks(): string {
	return __( 'Thank you — we will come back to you shortly.', 'severus-noir' );
}

/**
 * The form as a shortcode, so any page can carry it.
 */
function severus_contact_form_shortcode_render(): string {
	ob_start();
	severus_component( 'contact-form' );

	return (string) ob_get_clean();
}
add_shortcode( 'severus_contact_form', 'severus_contact_form_shortcode_render' );
