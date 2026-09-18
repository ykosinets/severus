<?php
/**
 * Native block editor integration.
 *
 * Editorial content is built from core Gutenberg blocks — there are no custom
 * block types here. Colours, spacing and typography come from theme.json, and
 * the additions below are style variations and patterns that let an editor
 * reach the theme's own looks with core blocks alone.
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
 * Pattern category for the theme's own compositions.
 */
function severus_register_pattern_category(): void {
	register_block_pattern_category(
		'severus',
		array( 'label' => __( 'Severus', 'severus-noir' ) )
	);
}
add_action( 'init', 'severus_register_pattern_category' );
