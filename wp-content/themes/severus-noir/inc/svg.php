<?php
/**
 * Inlining for the service glyphs.
 *
 * The icons have to be inlined rather than referenced with <img> or <use>: the
 * card draws their strokes on with CSS, and neither of those can be styled.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * The 120x120 rounded square every glyph is drawn on — the card supplies that
 * surface itself, so the plate is dropped.
 */
const SEVERUS_GLYPH_BACKPLATE = '/<path d="M105 0H15C6\.71573[^"]*"[^>]*\/?>(?:<\/path>)?/';

/**
 * Source palette mapped onto design tokens. The bright greens become
 * currentColor so a card can shift the whole glyph on hover.
 */
const SEVERUS_GLYPH_COLOURS = array(
	'#1D9E75' => 'currentColor',
	'#00B393' => 'currentColor',
	'#0F6E56' => 'var(--glyph-mid)',
	'#0D2E25' => 'var(--glyph-well)',
	'#0D261F' => 'var(--glyph-well)',
	'#0D1F1A' => 'var(--glyph-well)',
);

/**
 * Read an SVG attachment and return markup ready to drop into a template.
 *
 * @param mixed $field ACF image field pointing at an SVG.
 */
function severus_inline_svg( $field ): string {
	$image = severus_image( $field );

	if ( ! $image || ! $image['url'] || ! str_ends_with( strtolower( parse_url( $image['url'], PHP_URL_PATH ) ?? '' ), '.svg' ) ) {
		return '';
	}

	$key    = 'severus_glyph_' . md5( $image['url'] );
	$cached = get_transient( $key );

	if ( is_string( $cached ) ) {
		return $cached;
	}

	$path = severus_local_path( $image['url'] );

	if ( ! $path || ! file_exists( $path ) ) {
		return '';
	}

	$svg = severus_prepare_glyph( (string) file_get_contents( $path ), $key ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	set_transient( $key, $svg, WEEK_IN_SECONDS );

	return $svg;
}

/**
 * Turn an uploads URL into a path on disk, or null if it isn't local.
 */
function severus_local_path( string $url ): ?string {
	$uploads = wp_get_upload_dir();

	if ( ! str_starts_with( $url, $uploads['baseurl'] ) ) {
		return null;
	}

	return $uploads['basedir'] . substr( $url, strlen( $uploads['baseurl'] ) );
}

/**
 * Strip, recolour and normalise one glyph.
 *
 * @param string $svg   Raw file contents.
 * @param string $scope Unique prefix for the ids inside the file.
 */
function severus_prepare_glyph( string $svg, string $scope ): string {
	$svg = preg_replace( '/<\?xml[^>]*\?>\s*/', '', $svg );
	$svg = preg_replace( '/<desc>.*?<\/desc>/s', '', $svg );
	$svg = preg_replace( SEVERUS_GLYPH_BACKPLATE, '', $svg, 1 );

	// ids repeat across the icon set; namespace them so inlining is safe.
	if ( preg_match_all( '/id="([^"]+)"/', $svg, $matches ) ) {
		foreach ( array_unique( $matches[1] ) as $id ) {
			$svg = str_replace( array( 'id="' . $id . '"', 'url(#' . $id . ')' ), array( 'id="' . $scope . '-' . $id . '"', 'url(#' . $scope . '-' . $id . ')' ), $svg );
		}
	}

	foreach ( SEVERUS_GLYPH_COLOURS as $from => $to ) {
		$svg = str_ireplace( '"' . $from . '"', '"' . $to . '"', $svg );
	}

	// pathLength normalises every stroke to 1, so one CSS rule draws them all.
	$svg = preg_replace( '/<path(?=[^>]*\sstroke=)/', '<path pathLength="1"', $svg );

	$svg = preg_replace( '/<svg /', '<svg class="glyph" ', $svg, 1 );
	$svg = preg_replace( '/\s(?:width|height)="120"/', '', $svg, 2 );

	return trim( (string) preg_replace( '/>\s+</', '><', $svg ) );
}
