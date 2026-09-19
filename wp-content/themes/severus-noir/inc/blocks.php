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
 * The theme's blocks, each from its own block.json.
 */
function severus_register_blocks(): void {
	foreach ( (array) glob( get_theme_file_path( 'blocks/*/block.json' ) ) as $manifest ) {
		$name = basename( dirname( $manifest ) );

		register_block_type(
			dirname( $manifest ),
			array( 'render_callback' => 'severus_render_' . str_replace( '-', '_', $name ) . '_block' )
		);
	}
}
add_action( 'init', 'severus_register_blocks', 10 );

/**
 * The front page's composition is fixed: the editor fills the sections in, it
 * does not choose them. The template is built from the blocks already saved,
 * so it follows the page rather than duplicating the order in a second place,
 * and the lock is one setting to relax when sections should become movable.
 *
 * @param array<string, mixed> $settings Block editor settings.
 * @param WP_Block_Editor_Context $context Which editor is being set up.
 * @return array<string, mixed>
 */
function severus_lock_front_page_template( array $settings, $context ): array {
	$post = $context->post ?? null;

	if ( ! $post instanceof WP_Post || (int) $post->ID !== (int) get_option( 'page_on_front' ) ) {
		return $settings;
	}

	$template = array();

	foreach ( parse_blocks( (string) $post->post_content ) as $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue;
		}

		$template[] = array( $block['blockName'], (array) ( $block['attrs'] ?? array() ) );
	}

	if ( $template ) {
		$settings['template']     = $template;
		$settings['templateLock'] = 'all';
	}

	return $settings;
}
add_filter( 'block_editor_settings_all', 'severus_lock_front_page_template', 10, 2 );

/**
 * A section that still reads its own fields.
 *
 * @param array<string, mixed> $attributes Block attributes.
 */
function severus_render_section_block( array $attributes ): string {
	$name = (string) ( $attributes['name'] ?? '' );

	if ( '' === $name || ! preg_match( '/^[a-z0-9-]+$/', $name ) ) {
		return '';
	}

	ob_start();
	severus_component( $name );

	return (string) ob_get_clean();
}

/**
 * "Sound familiar": the header from the block, the slabs from its children.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Rendered inner blocks — unused, the
 *                                         slabs are read as data instead.
 * @param WP_Block             $block      The block, for its parsed children.
 */
function severus_render_familiar_block( array $attributes, string $content = '', ?WP_Block $block = null ): string {
	$cards = array();

	foreach ( (array) ( $block->parsed_block['innerBlocks'] ?? array() ) as $inner ) {
		if ( 'severus/familiar-card' !== ( $inner['blockName'] ?? '' ) ) {
			continue;
		}

		$card = (array) ( $inner['attrs'] ?? array() );

		/* Attributes hold what was typed; the line breaks are put in here,
		   the way the textarea fields these replace were formatted. */
		$cards[] = array(
			'card_image'   => (int) ( $card['imageId'] ?? 0 ),
			'card_heading' => nl2br( (string) ( $card['heading'] ?? '' ) ),
			'card_text'    => nl2br( (string) ( $card['text'] ?? '' ) ),
		);
	}

	ob_start();
	severus_component(
		'familiar',
		array(
			'title' => (string) ( $attributes['title'] ?? '' ),
			'intro' => nl2br( (string) ( $attributes['intro'] ?? '' ) ),
			'cards' => $cards,
		)
	);

	return (string) ob_get_clean();
}

/**
 * A slab renders as part of its stack, never on its own.
 */
function severus_render_familiar_card_block(): string {
	return '';
}
