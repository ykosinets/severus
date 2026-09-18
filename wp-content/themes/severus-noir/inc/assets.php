<?php
/**
 * Built asset loading.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Version assets by file mtime, so a rebuild busts the cache without a bump.
 */
function severus_asset_version( string $relative ): string {
	$path = get_theme_file_path( $relative );

	return file_exists( $path ) ? (string) filemtime( $path ) : SEVERUS_VERSION;
}

/**
 * Enqueue the built bundle. esbuild emits ES modules with code splitting, so
 * the script tag needs type="module" — see severus_module_tag() below.
 */
function severus_enqueue(): void {
	wp_enqueue_style(
		'severus-main',
		get_theme_file_uri( 'assets/dist/main.css' ),
		array(),
		severus_asset_version( 'assets/dist/main.css' )
	);

	wp_enqueue_script(
		'severus-main',
		get_theme_file_uri( 'assets/dist/main.js' ),
		array(),
		severus_asset_version( 'assets/dist/main.js' ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'severus_enqueue' );

/**
 * The bundle is ESM; a classic script tag would fail on the import statements.
 */
function severus_module_tag( string $tag, string $handle ): string {
	if ( 'severus-main' !== $handle ) {
		return $tag;
	}

	return str_replace( '<script ', '<script type="module" ', $tag );
}
add_filter( 'script_loader_tag', 'severus_module_tag', 10, 2 );

/**
 * Preload the face the first screen is set in.
 *
 * The stylesheet only reveals the font two round trips in — parse the CSS, then
 * fetch the file — which is long enough for the fallback to be laid out and
 * then replaced. Preloading collapses that to one.
 */
function severus_preload_font(): void {
	$file = 'assets/fonts/outfit-latin.woff2';

	if ( ! file_exists( get_theme_file_path( $file ) ) ) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( $file ) )
	);
}
add_action( 'wp_head', 'severus_preload_font', 2 );

/**
 * e-Ukraine, but only if it is actually there.
 *
 * Declaring the faces unconditionally costs four failed requests on every page
 * — the family is first in the stack, so the browser tries it every time.
 */
function severus_local_font_faces(): void {
	$faces = array(
		array( 'e-Ukraine-Light.woff2', 'e-Ukraine', 300 ),
		array( 'e-Ukraine-Regular.woff2', 'e-Ukraine', 400 ),
		array( 'e-Ukraine-Medium.woff2', 'e-Ukraine', 500 ),
		array( 'e-UkraineHead-Regular.woff2', 'e-Ukraine Head', 400 ),
		array( 'e-UkraineHead-Medium.woff2', 'e-Ukraine Head', 500 ),
	);

	$css = '';

	foreach ( $faces as list( $file, $family, $weight ) ) {
		if ( ! file_exists( get_theme_file_path( "assets/fonts/{$file}" ) ) ) {
			continue;
		}

		$css .= sprintf(
			'@font-face{font-family:"%s";src:url("%s") format("woff2");font-weight:%d;font-style:normal;font-display:swap}',
			$family,
			esc_url( get_theme_file_uri( "assets/fonts/{$file}" ) ),
			$weight
		);
	}

	if ( $css ) {
		wp_add_inline_style( 'severus-main', $css );
	}
}
/* Not hooked: the site is set in Outfit, as the previous theme was. The
   e-Ukraine files stay in assets/fonts; re-add this hook (and the family to
   --font / --font-display in tokens.pcss) to switch back. */
// add_action( 'wp_enqueue_scripts', 'severus_local_font_faces', 20 );
