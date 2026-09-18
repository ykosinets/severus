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
	add_editor_style( 'assets/dist/main.css' );

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
