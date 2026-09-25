<?php
/**
 * Theme supports and registrations.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Declare what the theme supports. Editor features are left to theme.json so
 * the front end and the block editor stay in step.
 */
function severus_setup(): void {
	load_theme_textdomain( 'severus-noir', get_theme_file_path( 'languages' ) );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 223, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	/* No add_editor_style(): the site bundle carries page-level rules (dark
	   body, reveal animations) that broke the classic editor's TinyMCE frame. */

	register_nav_menus(
		array(
			'primary'         => __( 'Primary', 'severus-noir' ),
			'footer_pages'    => __( 'Footer — main pages', 'severus-noir' ),
			'footer_services' => __( 'Footer — services', 'severus-noir' ),
		)
	);
}
add_action( 'after_setup_theme', 'severus_setup' );

/**
 * Content width, used by embeds and wide alignments.
 */
function severus_content_width(): void {
	$GLOBALS['content_width'] = 1140;
}
add_action( 'after_setup_theme', 'severus_content_width', 0 );

/**
 * Keep the site's established classic editing workflow without depending on
 * the Classic Editor plugin. Existing block markup still renders normally.
 */
add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

/** Pages are edited through ACF and do not accept visitor comments or pings. */
function severus_disable_page_comment_support(): void {
	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'page', 'trackbacks' );
}
add_action( 'init', 'severus_disable_page_comment_support', 20 );

/**
 * Reject comments and pings for every page, including pages whose legacy
 * `comment_status` may still be set to open in the database.
 *
 * @param bool $open    Whether the current item accepts comments or pings.
 * @param int  $post_id Post being checked.
 */
function severus_page_discussion_open( bool $open, int $post_id ): bool {
	return 'page' === get_post_type( $post_id ) ? false : $open;
}
add_filter( 'comments_open', 'severus_page_discussion_open', 10, 2 );
add_filter( 'pings_open', 'severus_page_discussion_open', 10, 2 );

/**
 * Keep the admin sidebar to what editors use. The screens stay reachable by
 * URL; only their menu entries are removed. Theme settings is its own
 * top-level ACF options page, so it stays.
 */
function severus_trim_admin_menu(): void {
	remove_menu_page( 'edit.php?post_type=acf-field-group' );
	remove_menu_page( 'forminator' );
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'severus_trim_admin_menu', 999 );

/**
 * Theme settings sits just above Appearance and Media just below it.
 *
 * Everything that is not WordPress itself, a post type or Theme settings is a
 * plugin screen, and goes to the bottom of the sidebar, just above "Collapse
 * menu" — so a newly installed plugin lands there too. The ones listed in
 * $last come first, in that order; any others follow in their own order.
 *
 * @param array $order Menu slugs in display order.
 */
function severus_admin_menu_order( array $order ): array {
	$core = array(
		'index.php',
		'separator1',
		'edit.php',
		'upload.php',
		'link-manager.php',
		'edit-comments.php',
		'separator2',
		'themes.php',
		'plugins.php',
		'users.php',
		'profile.php',
		'tools.php',
		'options-general.php',
		'separator-last',
		'theme-settings',
	);
	$last = array(
		'wpseo_dashboard',
		'cookie-law-info',
		'wpcode',
		'newsletter_main_index',
		'googlesitekit-dashboard',
		'googlesitekit-splash', // Site Kit before it is set up.
		'copy-delete-posts',
	);

	$stays  = static fn( string $slug ): bool => in_array( $slug, $core, true ) || str_starts_with( $slug, 'edit.php?post_type=' );
	$plugin = array_values( array_filter( $order, static fn( $slug ): bool => ! $stays( (string) $slug ) ) );

	$tail = array_merge( array_values( array_intersect( $last, $plugin ) ), array_values( array_diff( $plugin, $last ) ) );
	$rest = array_values( array_diff( $order, $plugin ) );

	if ( in_array( 'themes.php', $rest, true ) ) {
		$around = array_values( array_intersect( array( 'theme-settings', 'themes.php', 'upload.php' ), $rest ) );
		$rest   = array_values( array_diff( $rest, array( 'theme-settings', 'upload.php' ) ) );
		array_splice( $rest, array_search( 'themes.php', $rest, true ), 1, $around );
	}

	return array_merge( $rest, $tail );
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'severus_admin_menu_order', 999 );
