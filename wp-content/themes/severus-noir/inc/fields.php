<?php
/**
 * How the theme reads its content fields.
 *
 * Templates ask severus_field() instead of calling the field plugin, so what
 * stores the content is a decision made in this one file. ACF answers today.
 * When it goes, the body of this function changes and the templates do not —
 * which is the difference between one edit and a hundred and forty.
 *
 * A missing plugin gives null rather than a fatal, so the site still renders
 * with the fields empty.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * A field's value.
 *
 * @param string          $name   Field name.
 * @param int|string|null $source Post id, 'option' for the theme settings, or
 *                                null for the post being rendered.
 * @return mixed Value, shaped by the field's type: a string for text, an
 *               attachment array for images, a row array for repeaters.
 */
function severus_field( string $name, $source = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return null;
	}

	return get_field( $name, $source ?? false );
}

/**
 * A repeater's rows, each keyed by sub-field name. Always an array, so it can
 * be walked without a guard.
 *
 * @param string          $name   Repeater field name.
 * @param int|string|null $source As severus_field().
 * @return array<int, array<string, mixed>>
 */
function severus_rows( string $name, $source = null ): array {
	$rows = severus_field( $name, $source );

	return is_array( $rows ) ? $rows : array();
}
