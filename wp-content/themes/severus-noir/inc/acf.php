<?php
/**
 * The theme's field groups and the editor tweaks that go with them.
 *
 * Every group is a file under inc/field-groups/ — the ones exported from the
 * database and the two the theme wrote itself, in the same shape, so there is
 * one place a field is defined and one place to read definitions from.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * The field groups that used to live as rows in the database, one file per
 * group under inc/field-groups/.
 *
 * Moving them into the theme means a deploy carries the field structure with
 * it, instead of the structure and the code drifting apart between
 * environments. The trade is that ACF shows groups registered in code as
 * read-only: their definitions are edited in those files.
 */
function severus_register_exported_field_groups(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	foreach ( (array) glob( get_theme_file_path( 'inc/field-groups/*.php' ) ) as $file ) {
		$group = require $file;

		if ( is_array( $group ) ) {
			acf_add_local_field_group( $group );
		}
	}
}
add_action( 'acf/include_fields', 'severus_register_exported_field_groups' );

/**
 * The free-text "Industry" line in the case info group is replaced by the
 * case_category taxonomy. The group lives in the database, so the field is
 * hidden from the editor here rather than deleted there: it disappears on
 * every environment the theme is deployed to, and the old values stay in
 * post meta until the field is removed from the group in ACF.
 */
add_filter( 'acf/prepare_field/key=field_661787b59fd7d', '__return_false' );

/**
 * The services shown on the home page and the Services page are the top-level
 * services in their own order, so the lists that used to pick and order them
 * by hand — one on each page's field group — no longer decide anything.
 * Hidden rather than deleted, for the same reason as above: reordering a list
 * that changes nothing is worse than not seeing it.
 *
 * Services are ordered in the Order box on the service itself.
 */
add_filter( 'acf/prepare_field/key=field_6a59e89a0b4c2', '__return_false' );
add_filter( 'acf/prepare_field/key=field_66177695ab6b6', '__return_false' );
