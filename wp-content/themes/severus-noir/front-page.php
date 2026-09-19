<?php
/**
 * The home page: a list of section blocks, in the order the editor holds them.
 *
 * The blocks are rendered directly rather than through the_content(), because
 * the page is a composition of sections, not prose: the content filters would
 * texturize and wrap markup the components have already finished.
 *
 * A page with no blocks yet falls back to the sequence the theme shipped with,
 * so the move to blocks can be made one page at a time.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/* The order the front page was built in, before its sections became blocks. */
const SEVERUS_FRONT_SECTIONS = array(
	'hero',
	'familiar',
	'turn',
	'services',
	'method',
	'impact',
	'results',
	'trust',
	'voices',
	'journal',
	'people',
	'faq',
	'talk',
);

get_header();

$content = get_post_field( 'post_content', get_the_ID() );

if ( has_blocks( $content ) ) {
	foreach ( parse_blocks( $content ) as $block ) {
		if ( ! empty( $block['blockName'] ) ) {
			echo render_block( $block ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — blocks render their own escaped markup.
		}
	}
} else {
	foreach ( SEVERUS_FRONT_SECTIONS as $section ) {
		severus_component( $section );
	}
}

get_footer();
