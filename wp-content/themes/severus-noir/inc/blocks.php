<?php
/**
 * Native block editor integration.
 *
 * Editorial content is built from core Gutenberg blocks. On top of those the
 * theme registers its own section blocks, under blocks/: each is a dynamic
 * block whose render callback is the matching component, so the markup has
 * one home and a template change reaches content that is already published.
 *
 * Sections not yet moved to a block of their own are stood in for by
 * severus/section, which renders its component from the page's fields exactly
 * as before. That is what lets the move happen one section at a time.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block style variations, so the design's surfaces are available to core blocks.
 */
function severus_register_block_styles(): void {
	register_block_style( 'core/group', array( 'name' => 'edge', 'label' => __( 'Gradient edge', 'severus-noir' ) ) );
	register_block_style( 'core/columns', array( 'name' => 'edge', 'label' => __( 'Gradient edge', 'severus-noir' ) ) );
	register_block_style( 'core/separator', array( 'name' => 'rule', 'label' => __( 'Emerald rule', 'severus-noir' ) ) );
	register_block_style( 'core/button', array( 'name' => 'quiet', 'label' => __( 'Quiet', 'severus-noir' ) ) );
	register_block_style( 'core/heading', array( 'name' => 'display', 'label' => __( 'Display', 'severus-noir' ) ) );
	register_block_style( 'core/image', array( 'name' => 'frame', 'label' => __( 'Framed', 'severus-noir' ) ) );
	register_block_style( 'core/quote', array( 'name' => 'card', 'label' => __( 'Card', 'severus-noir' ) ) );
}
add_action( 'init', 'severus_register_block_styles' );

/**
 * Pattern and block category for the theme's own compositions.
 */
function severus_register_pattern_category(): void {
	register_block_pattern_category(
		'severus',
		array( 'label' => __( 'Severus', 'severus-noir' ) )
	);
}
add_action( 'init', 'severus_register_pattern_category' );

add_filter(
	'block_categories_all',
	static function ( array $categories ): array {
		array_unshift(
			$categories,
			array(
				'slug'  => 'severus',
				'title' => __( 'Severus sections', 'severus-noir' ),
			)
		);

		return $categories;
	}
);

/**
 * The editor bundle every section block declares as its script. Registered
 * once by hand so the blocks can share one file instead of loading thirty.
 */
function severus_register_block_assets(): void {
	$file = get_theme_file_path( 'assets/dist/editor.js' );

	wp_register_script(
		'severus-editor',
		get_theme_file_uri( 'assets/dist/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n', 'wp-data' ),
		file_exists( $file ) ? (string) filemtime( $file ) : SEVERUS_VERSION,
		true
	);
}
add_action( 'init', 'severus_register_block_assets', 5 );

/**
 * The theme's blocks, each from its own block.json. Section blocks share one
 * render callback, which reads what to do from severus_block_sections().
 */
function severus_register_blocks(): void {
	foreach ( (array) glob( get_theme_file_path( 'blocks/*/block.json' ) ) as $manifest ) {
		register_block_type( dirname( $manifest ), array( 'render_callback' => 'severus_render_block' ) );
	}
}
add_action( 'init', 'severus_register_blocks', 10 );

/**
 * Any of the theme's blocks.
 *
 * A section renders its component with the data its attributes describe. The
 * bridge block renders a component that still reads its own fields. A row
 * block renders nothing on its own — its parent has already read it.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Inner markup, unused.
 * @param WP_Block             $block      The block, for its parsed children.
 */
function severus_render_block( array $attributes, string $content = '', ?WP_Block $block = null ): string {
	$name = (string) ( $block->name ?? '' );

	if ( 'severus/section' === $name ) {
		$section = (string) ( $attributes['name'] ?? '' );

		if ( '' === $section || ! preg_match( '/^[a-z0-9-]+$/', $section ) ) {
			return '';
		}

		ob_start();
		severus_component( $section );

		return (string) ob_get_clean();
	}

	$section = substr( $name, strlen( 'severus/' ) );
	$schema  = severus_block_sections()[ $section ] ?? null;

	if ( ! $schema ) {
		return '';
	}

	$data = severus_render_section_data(
		$section,
		$attributes,
		(array) ( $block->parsed_block['innerBlocks'] ?? array() )
	);

	ob_start();
	severus_component( (string) ( $schema['component'] ?? $section ), $data );

	return (string) ob_get_clean();
}
