<?php
/**
 * Post types and taxonomies the theme owns.
 *
 * Services, cases and reviews used to be registered by ACF records in the
 * database (the `acf-post-type` posts). They are registered here instead, so
 * they ship with the theme and outlive the plugin. Nothing has to be deleted
 * in ACF for the handover: ACF registers a type only when it does not exist
 * yet (ACF_Post_Type::register_post_types()), and this file runs at `init`
 * priority 0, ahead of ACF's priority 5 — so ACF steps aside by itself, on
 * every environment, the moment the theme is deployed.
 *
 * Arguments match what ACF produced, with two deliberate changes to `service`:
 * `page-attributes` support, which gives the parent and order controls the
 * two-level service tree is built on, and rewriting owned here, so child
 * services keep flat URLs — see severus_service_link() below.
 *
 * Industries were registered by the previous theme, so they have to be
 * registered here or their pages 404. Case categories are the theme's own
 * taxonomy for the `case` type.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

function severus_register_post_types(): void {
	register_post_type(
		'service',
		array(
			'labels'           => array(
				'name'               => __( 'Services', 'severus-noir' ),
				'singular_name'      => __( 'Service', 'severus-noir' ),
				'menu_name'          => __( 'Services', 'severus-noir' ),
				'add_new_item'       => __( 'Add New Service', 'severus-noir' ),
				'edit_item'          => __( 'Edit Service', 'severus-noir' ),
				'view_item'          => __( 'View Service', 'severus-noir' ),
				'view_items'         => __( 'View Services', 'severus-noir' ),
				'all_items'          => __( 'All Services', 'severus-noir' ),
				'search_items'       => __( 'Search Services', 'severus-noir' ),
				'parent_item_colon'  => __( 'Parent service:', 'severus-noir' ),
				'not_found'          => __( 'No services found.', 'severus-noir' ),
				'not_found_in_trash' => __( 'No services found in Trash.', 'severus-noir' ),
				'attributes'         => __( 'Service attributes', 'severus-noir' ),
			),
			'public'           => true,
			'hierarchical'     => true,
			'show_in_rest'     => true,
			'has_archive'      => false,
			'delete_with_user' => false,
			'supports'         => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			/*
			 * Rewriting is the theme's, not the core default for a hierarchical
			 * type: that one builds /service/<parent>/<child>/ and resolves it
			 * through get_page_by_path(), which needs the whole path. The rules
			 * below keep every service one segment deep whatever its parent is,
			 * and query_var => false keeps WP_Query from turning the request
			 * back into a path lookup (see class-wp-query.php, 'pagename').
			 */
			'rewrite'          => false,
			'query_var'        => false,
		)
	);

	add_rewrite_rule( '^service/([^/]+)/?$', 'index.php?post_type=service&name=$matches[1]', 'top' );
	add_rewrite_rule( '^service/([^/]+)/page/([0-9]{1,})/?$', 'index.php?post_type=service&name=$matches[1]&page=$matches[2]', 'top' );

	register_post_type(
		'case',
		array(
			'labels'           => array(
				'name'               => __( 'Cases', 'severus-noir' ),
				'singular_name'      => __( 'Case', 'severus-noir' ),
				'menu_name'          => __( 'Cases', 'severus-noir' ),
				'add_new_item'       => __( 'Add New Case', 'severus-noir' ),
				'edit_item'          => __( 'Edit Case', 'severus-noir' ),
				'view_item'          => __( 'View Case', 'severus-noir' ),
				'view_items'         => __( 'View Cases', 'severus-noir' ),
				'all_items'          => __( 'All Cases', 'severus-noir' ),
				'search_items'       => __( 'Search Cases', 'severus-noir' ),
				'not_found'          => __( 'No cases found.', 'severus-noir' ),
				'not_found_in_trash' => __( 'No cases found in Trash.', 'severus-noir' ),
			),
			'public'           => true,
			'hierarchical'     => false,
			'show_in_rest'     => true,
			'has_archive'      => 'cases',
			'delete_with_user' => false,
			'supports'         => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'          => array( 'slug' => 'case', 'with_front' => true, 'pages' => true, 'feeds' => false ),
		)
	);

	register_post_type(
		'review',
		array(
			'labels'              => array(
				'name'               => __( 'Reviews', 'severus-noir' ),
				'singular_name'      => __( 'Review', 'severus-noir' ),
				'menu_name'          => __( 'Reviews', 'severus-noir' ),
				'add_new_item'       => __( 'Add New Review', 'severus-noir' ),
				'edit_item'          => __( 'Edit Review', 'severus-noir' ),
				'view_item'          => __( 'View Review', 'severus-noir' ),
				'view_items'         => __( 'View Reviews', 'severus-noir' ),
				'all_items'          => __( 'All Reviews', 'severus-noir' ),
				'search_items'       => __( 'Search Reviews', 'severus-noir' ),
				'not_found'          => __( 'No reviews found.', 'severus-noir' ),
				'not_found_in_trash' => __( 'No reviews found in Trash.', 'severus-noir' ),
			),
			/* Reviews are shown inside other pages; they have no page of their own. */
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'delete_with_user'    => false,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'             => array( 'slug' => 'review', 'with_front' => false, 'pages' => false, 'feeds' => false ),
		)
	);

	register_post_type(
		'industry',
		array(
			'labels'        => array(
				'name'               => __( 'Industries', 'severus-noir' ),
				'singular_name'      => __( 'Industry', 'severus-noir' ),
				'menu_name'          => __( 'Industries', 'severus-noir' ),
				'add_new_item'       => __( 'Add New Industry', 'severus-noir' ),
				'edit_item'          => __( 'Edit Industry', 'severus-noir' ),
				'view_item'          => __( 'View Industry', 'severus-noir' ),
				'all_items'          => __( 'All Industries', 'severus-noir' ),
				'search_items'       => __( 'Search Industries', 'severus-noir' ),
				'not_found'          => __( 'No industries found.', 'severus-noir' ),
				'not_found_in_trash' => __( 'No industries found in Trash.', 'severus-noir' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'rewrite'       => array( 'slug' => 'industry' ),
			'has_archive'   => false,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-building',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'case_category',
		array( 'case' ),
		array(
			'labels'            => array(
				'name'              => __( 'Case categories', 'severus-noir' ),
				'singular_name'     => __( 'Case category', 'severus-noir' ),
				'menu_name'         => __( 'Categories', 'severus-noir' ),
				'all_items'         => __( 'All categories', 'severus-noir' ),
				'edit_item'         => __( 'Edit category', 'severus-noir' ),
				'view_item'         => __( 'View category', 'severus-noir' ),
				'update_item'       => __( 'Update category', 'severus-noir' ),
				'add_new_item'      => __( 'Add new category', 'severus-noir' ),
				'new_item_name'     => __( 'New category name', 'severus-noir' ),
				'parent_item'       => __( 'Parent category', 'severus-noir' ),
				'search_items'      => __( 'Search categories', 'severus-noir' ),
				'not_found'         => __( 'No categories found.', 'severus-noir' ),
				'back_to_items'     => __( '← Back to categories', 'severus-noir' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'cases/category', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'severus_register_post_types', 0 );

/**
 * A service's URL: /service/<slug>/, whatever its parent is.
 *
 * The type is hierarchical so services can be grouped, but grouping is an
 * editorial decision and must not rewrite public addresses: re-parenting a
 * service in the editor would otherwise change the URL of a page that is
 * already indexed and linked.
 *
 * Unpublished posts keep the permalink WordPress built, which carries the id
 * rather than a slug. $leavename leaves the %postname% token in place for the
 * slug editor in the admin.
 */
function severus_service_link( string $permalink, WP_Post $post, bool $leavename ): string {
	if ( 'service' !== $post->post_type ) {
		return $permalink;
	}

	if ( '' === $post->post_name || in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ), true ) ) {
		return $permalink;
	}

	return home_url( user_trailingslashit( 'service/' . ( $leavename ? '%postname%' : $post->post_name ) ) );
}
add_filter( 'post_type_link', 'severus_service_link', 10, 3 );

/**
 * Service slugs are unique across the whole type, not just among siblings.
 *
 * WordPress scopes slugs to a parent for hierarchical types, which is right
 * when the URL carries the parent's slug too. Ours doesn't, so two services
 * under different parents could claim the same address and one would hide the
 * other. Core has already deduplicated among siblings by the time this runs.
 */
function severus_service_unique_slug( string $slug, int $post_id, string $post_status, string $post_type ): string {
	global $wpdb;

	if ( 'service' !== $post_type ) {
		return $slug;
	}

	$candidate = $slug;
	$suffix    = 2;

	while ( (string) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->prepare(
			"SELECT post_name FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'service' AND ID != %d LIMIT 1",
			$candidate,
			$post_id
		)
	) !== '' ) {
		$candidate = $slug . '-' . $suffix;
		++$suffix;
	}

	return $candidate;
}
add_filter( 'wp_unique_post_slug', 'severus_service_unique_slug', 10, 4 );

/**
 * The service tree is two levels deep: a service is either a parent or a
 * child of one. The parent dropdown offers top-level services only.
 */
function severus_service_parent_choices( array $args, WP_Post $post ): array {
	if ( 'service' === $post->post_type ) {
		$args['depth'] = 1;
	}

	return $args;
}
add_filter( 'page_attributes_dropdown_pages_args', 'severus_service_parent_choices', 10, 2 );

/**
 * The same rule, enforced on save: a parent set to a child service — through
 * the REST API, an import or quick edit — is lifted to that child's own
 * parent, so the service stays in the family it was put in and the tree never
 * grows a third level.
 */
function severus_service_depth( array $data, array $postarr ): array {
	if ( 'service' !== $data['post_type'] || empty( $data['post_parent'] ) ) {
		return $data;
	}

	$parent = (int) $data['post_parent'];
	$id     = (int) ( $postarr['ID'] ?? 0 );
	$seen   = array();

	while ( $parent && ! in_array( $parent, $seen, true ) ) {
		$seen[]      = $parent;
		$grandparent = (int) wp_get_post_parent_id( $parent );

		if ( ! $grandparent ) {
			break;
		}

		$parent = $grandparent;
	}

	$data['post_parent'] = ( $parent === $id ) ? 0 : $parent;

	return $data;
}
add_filter( 'wp_insert_post_data', 'severus_service_depth', 10, 2 );

/**
 * Rewrite rules are rebuilt once per version of this file's registrations —
 * on theme switch, and on the first request after a deploy that changes them.
 */
const SEVERUS_REWRITE_VERSION = '3';

function severus_flush_rewrites(): void {
	severus_register_post_types();
	flush_rewrite_rules( false );
	update_option( 'severus_rewrite_version', SEVERUS_REWRITE_VERSION );
}
add_action( 'after_switch_theme', 'severus_flush_rewrites' );

add_action(
	'init',
	static function (): void {
		if ( get_option( 'severus_rewrite_version' ) !== SEVERUS_REWRITE_VERSION ) {
			severus_flush_rewrites();
		}
	},
	99
);
