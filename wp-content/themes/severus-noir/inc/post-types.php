<?php
/**
 * Post types the theme owns.
 *
 * Services, cases and reviews are registered by ACF. Industries were
 * registered by the previous theme, so they have to be registered here or
 * their pages 404. Same slug and arguments as before, so URLs don't change.
 *
 * Case categories are the theme's own taxonomy for the ACF `case` type.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/** Expose native parent and order controls after ACF registers services. */
add_action(
	'init',
	static function (): void {
		add_post_type_support( 'service', 'page-attributes' );
	},
	20
);

/**
 * Menu icons for the ACF-registered types, kept in code rather than in ACF's
 * stored settings.
 *
 * @param array  $args      Post type registration arguments.
 * @param string $post_type Post type key.
 */
function severus_post_type_icons( array $args, string $post_type ): array {
	$icons = array(
		'case'    => 'dashicons-portfolio',
		'review'  => 'dashicons-testimonial',
		'service' => 'dashicons-admin-tools',
	);
	if ( isset( $icons[ $post_type ] ) ) {
		$args['menu_icon'] = $icons[ $post_type ];
	}
	return $args;
}
add_filter( 'register_post_type_args', 'severus_post_type_icons', 10, 2 );

function severus_register_post_types(): void {
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
			'menu_position' => 29,
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
add_action( 'init', 'severus_register_post_types' );

/**
 * Rewrite rules are rebuilt once per version of this file's registrations —
 * on theme switch, and on the first request after a deploy that changes them.
 */
const SEVERUS_REWRITE_VERSION = '2';

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
