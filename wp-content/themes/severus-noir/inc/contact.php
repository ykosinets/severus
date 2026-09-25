<?php
/**
 * Forminator contact form and access to legacy enquiries.
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
 * The form as a shortcode, so any page can carry it.
 */
function severus_contact_form_shortcode_render(): string {
	ob_start();
	severus_component( 'contact-form' );

	return (string) ob_get_clean();
}
add_shortcode( 'severus_contact_form', 'severus_contact_form_shortcode_render' );

/** Apply Noir presentation without replacing Forminator's fields or handlers. */
function severus_forminator_markup( string $html, $fields, $type, $settings ): string {
	if ( '82' !== (string) ( $settings['form_id'] ?? '' ) ) {
		return $html;
	}

	$tags = new WP_HTML_Tag_Processor( $html );
	while ( $tags->next_tag() ) {
		if ( $tags->has_class( 'forminator-field' ) ) {
			$tags->add_class( 'field' );
		}
		if ( $tags->has_class( 'forminator-input' ) || $tags->has_class( 'forminator-textarea' ) ) {
			$tags->set_attribute( 'placeholder', ' ' );
		}
		if ( $tags->has_class( 'forminator-description' ) ) {
			$tags->add_class( 'field__count' );
		}
		if ( $tags->has_class( 'forminator-button-submit' ) ) {
			$tags->add_class( 'btn--solid' );
			$tags->add_class( 'btn--block' );
			$tags->set_attribute( 'data-snake-arrow', '' );
		}
	}

	ob_start();
	severus_arrow();
	$arrow = (string) ob_get_clean();
	return preg_replace_callback(
		'/(<button\b[^>]*class="[^"]*forminator-button-submit[^"]*"[^>]*>)(.*?)(<\/button>)/s',
		static fn( $match ) => $match[1] . '<span>' . $match[2] . '</span>' . $arrow . $match[3],
		$tags->get_updated_html()
	);
}
add_filter( 'forminator_render_form_markup', 'severus_forminator_markup', 10, 4 );
