<?php
/**
 * Severus Noir.
 *
 * The theme is assembled from components: every folder under components/ owns
 * its template, its stylesheet and its script, and the build turns the last two
 * into assets/dist/. Nothing is registered by hand.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

define( 'SEVERUS_VERSION', '1.0.0' );

require_once get_theme_file_path( 'inc/setup.php' );
require_once get_theme_file_path( 'inc/post-types.php' );
require_once get_theme_file_path( 'inc/assets.php' );
require_once get_theme_file_path( 'inc/helpers.php' );
require_once get_theme_file_path( 'inc/cases.php' );
require_once get_theme_file_path( 'inc/svg.php' );
require_once get_theme_file_path( 'inc/blocks.php' );
require_once get_theme_file_path( 'inc/contact.php' );
require_once get_theme_file_path( 'inc/acf.php' );
