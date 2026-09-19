<?php
/**
 * How the theme reads its content fields.
 *
 * Templates ask severus_field(); nothing here calls the field plugin. Values
 * come from post meta and options the way ACF writes them — a field's value
 * under its own name, a repeater as a count plus `name_0_subfield` rows, a
 * group as `name_subfield` — and are shaped by the field's own definition,
 * which the theme carries in inc/field-groups/.
 *
 * So ACF stores and edits; the theme reads. Nothing on the front end depends
 * on the plugin being installed.
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
	$read        = severus_field_reader( $source );
	$definitions = severus_field_definitions();

	/*
	 * The field key is stored beside the value, under the same name with an
	 * underscore, and that reference is the only way a name is resolved. It
	 * says both which definition wrote this value — two groups may share a
	 * field name — and whether the field applies here at all: a post that was
	 * never saved with the field has no reference, and then there is nothing
	 * to shape, only whatever happens to sit under that meta name.
	 */
	$key   = $read( '_' . $name );
	$field = is_string( $key ) ? ( $definitions[ $key ] ?? null ) : null;

	if ( ! $field ) {
		return $read( $name );
	}

	return severus_field_value( $field, $name, $read );
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

/**
 * Reads one stored value by its meta name. Options live in wp_options under
 * an `options_` prefix, everything else in the post's meta.
 *
 * Returns null when nothing is stored, which a stored empty string is not —
 * the difference decides whether a field falls back to its default.
 *
 * @param int|string|null $source Post id, 'option', or null for current post.
 */
function severus_field_reader( $source ): callable {
	if ( 'option' === $source ) {
		return static function ( string $path ) {
			/*
			 * Options are prefixed, and a field's key reference keeps its
			 * leading underscore in front of the prefix: the value of `title`
			 * is `options_title`, its key is `_options_title`.
			 */
			$name     = str_starts_with( $path, '_' )
				? '_options_' . substr( $path, 1 )
				: 'options_' . $path;
			$sentinel = new stdClass();
			$value    = get_option( $name, $sentinel );

			return $value === $sentinel ? null : $value;
		};
	}

	$id = null === $source ? (int) get_the_ID() : (int) $source;

	return static function ( string $path ) use ( $id ) {
		if ( ! $id ) {
			return null;
		}

		$stored = get_post_meta( $id, $path, false );

		return $stored ? $stored[0] : null;
	};
}

/**
 * Every field the theme defines, by key, with sub-fields flattened in beside
 * their parents.
 *
 * @return array<string, array<string, mixed>>
 */
function severus_field_definitions(): array {
	static $definitions = null;

	if ( null !== $definitions ) {
		return $definitions;
	}

	$definitions = array();

	$flatten = static function ( array $fields ) use ( &$flatten, &$definitions ): void {
		foreach ( $fields as $field ) {
			if ( empty( $field['key'] ) ) {
				continue;
			}

			$definitions[ $field['key'] ] = $field;

			if ( ! empty( $field['sub_fields'] ) ) {
				$flatten( $field['sub_fields'] );
			}

			foreach ( (array) ( $field['layouts'] ?? array() ) as $layout ) {
				if ( ! empty( $layout['sub_fields'] ) ) {
					$flatten( $layout['sub_fields'] );
				}
			}
		}
	};

	foreach ( severus_field_groups() as $group ) {
		$flatten( (array) ( $group['fields'] ?? array() ) );
	}

	return $definitions;
}

/**
 * The field groups, one per file under inc/field-groups/.
 *
 * @return array<int, array<string, mixed>>
 */
function severus_field_groups(): array {
	static $groups = null;

	if ( null !== $groups ) {
		return $groups;
	}

	$groups = array();

	foreach ( (array) glob( get_theme_file_path( 'inc/field-groups/*.php' ) ) as $file ) {
		$group = require $file;

		if ( is_array( $group ) ) {
			$groups[] = $group;
		}
	}

	return $groups;
}

/**
 * One value, read and shaped by its field's type.
 *
 * @param array<string, mixed> $field Field definition.
 * @param string               $path  Meta name the value is stored under.
 * @param callable             $read  Reader from severus_field_reader().
 * @return mixed
 */
function severus_field_value( array $field, string $path, callable $read ) {
	$type = $field['type'] ?? 'text';

	/* Layout only — a tab holds no value of its own. */
	if ( in_array( $type, array( 'tab', 'message', 'accordion' ), true ) ) {
		return null;
	}

	if ( 'repeater' === $type ) {
		$count = $read( $path );

		/* No rows is false, not an empty array — what a repeater has always answered. */
		if ( ! $count || ! is_numeric( $count ) || empty( $field['sub_fields'] ) ) {
			return false;
		}

		$rows = array();

		for ( $i = 0; $i < (int) $count; $i++ ) {
			$rows[] = severus_field_subvalues( (array) $field['sub_fields'], $path . '_' . $i, $read );
		}

		return $rows;
	}

	if ( 'group' === $type ) {
		return severus_field_subvalues( (array) ( $field['sub_fields'] ?? array() ), $path, $read );
	}

	if ( 'flexible_content' === $type ) {
		$order = $read( $path );

		if ( empty( $order ) || ! is_array( $order ) || empty( $field['layouts'] ) ) {
			return false;
		}

		$layouts = array();

		foreach ( (array) ( $field['layouts'] ?? array() ) as $layout ) {
			$layouts[ $layout['name'] ] = (array) ( $layout['sub_fields'] ?? array() );
		}

		$rows = array();

		foreach ( array_values( $order ) as $i => $name ) {
			$rows[] = array( 'acf_fc_layout' => $name )
				+ severus_field_subvalues( $layouts[ $name ] ?? array(), $path . '_' . $i, $read );
		}

		return $rows;
	}

	$value = $read( $path );

	if ( null === $value && isset( $field['default_value'] ) ) {
		$value = $field['default_value'];
	}

	if ( null === $value ) {
		return null;
	}

	switch ( $type ) {
		case 'true_false':
			return (bool) $value;

		case 'textarea':
			$breaks = $field['new_lines'] ?? '';

			if ( 'br' === $breaks ) {
				return nl2br( (string) $value );
			}

			return 'wpautop' === $breaks ? wpautop( (string) $value ) : $value;

		case 'wysiwyg':
			return severus_field_the_content( (string) $value );

		case 'image':
		case 'file':
			return severus_field_attachment( $value, $field['return_format'] ?? 'array' );

		case 'link':
			$link = is_array( $value ) ? $value : array();

			return 'url' === ( $field['return_format'] ?? 'array' ) ? (string) ( $link['url'] ?? '' ) : $link;

		case 'post_object':
		case 'relationship':
			return severus_field_posts( $value, $field, $type );
	}

	return $value;
}

/**
 * The sub-values under a prefix — a repeater row, a group, a layout.
 *
 * @param array<int, array<string, mixed>> $fields Sub-field definitions.
 * @return array<string, mixed>
 */
function severus_field_subvalues( array $fields, string $prefix, callable $read ): array {
	$values = array();

	foreach ( $fields as $field ) {
		if ( empty( $field['name'] ) ) {
			continue;
		}

		$values[ $field['name'] ] = severus_field_value( $field, $prefix . '_' . $field['name'], $read );
	}

	return $values;
}

/**
 * An image or file value as the field asks for it: the attachment's id, its
 * url, or the details a template needs to render it.
 *
 * The array is deliberately narrower than the one ACF builds. Its extra keys —
 * filesize above all, which stats the file on disk for every image on the
 * page — are not read anywhere in the theme, and severus_image() takes only
 * url, alt, width and height from what it is given.
 *
 * @param mixed $value Stored value — an attachment id.
 * @return array<string, mixed>|int|string|null
 */
function severus_field_attachment( $value, string $format ) {
	$id = is_array( $value ) ? (int) ( $value['ID'] ?? $value['id'] ?? 0 ) : (int) $value;

	/* Nothing chosen, or the attachment is gone: false, whatever the format. */
	if ( ! $id || ! get_post( $id ) ) {
		return false;
	}

	if ( 'id' === $format ) {
		return $id;
	}

	if ( 'url' === $format ) {
		return (string) wp_get_attachment_url( $id );
	}

	$source = wp_get_attachment_image_src( $id, 'full' );
	$meta   = (array) wp_get_attachment_metadata( $id );

	return array(
		'ID'          => $id,
		'id'          => $id,
		'title'       => get_the_title( $id ),
		'filename'    => wp_basename( (string) get_attached_file( $id ) ),
		'url'         => $source ? $source[0] : (string) wp_get_attachment_url( $id ),
		'alt'         => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
		/* Video carries its dimensions in the attachment metadata, not in an image source. */
		'width'       => $source ? $source[1] : ( $meta['width'] ?? 0 ),
		'height'      => $source ? $source[2] : ( $meta['height'] ?? 0 ),
		'mime_type'   => get_post_mime_type( $id ),
		'description' => get_post_field( 'post_content', $id ),
		'caption'     => get_post_field( 'post_excerpt', $id ),
	);
}

/**
 * A post reference as the field asks for it: ids, or the posts themselves.
 * Ids that no longer resolve are dropped, so a template never meets a null.
 *
 * Empty answers differ by field, and templates rely on it: a relationship
 * hands back whatever was stored, a single post object hands back false.
 *
 * @param mixed                $value Stored id or ids.
 * @param array<string, mixed> $field Field definition.
 * @return mixed
 */
function severus_field_posts( $value, array $field, string $type ) {
	if ( empty( $value ) ) {
		return 'relationship' === $type ? $value : false;
	}

	$ids   = array_filter( array_map( 'intval', (array) $value ) );
	$posts = array();

	foreach ( $ids as $id ) {
		$post = get_post( $id );

		if ( $post ) {
			$posts[] = 'id' === ( $field['return_format'] ?? 'object' ) ? $id : $post;
		}
	}

	if ( 'post_object' === $type && empty( $field['multiple'] ) ) {
		return $posts ? $posts[0] : false;
	}

	return $posts;
}

/**
 * The filters a WYSIWYG value is run through before it is printed — the same
 * set, in the same order, that the editor's own content gets.
 */
function severus_field_the_content( string $value ): string {
	global $wp_embed;

	if ( $wp_embed instanceof WP_Embed ) {
		$value = $wp_embed->autoembed( $wp_embed->run_shortcode( $value ) );
	}

	$value = wptexturize( $value );
	$value = wpautop( $value );
	$value = shortcode_unautop( $value );
	$value = wp_filter_content_tags( $value );
	$value = capital_P_dangit( $value );
	$value = do_shortcode( $value );

	return convert_smilies( $value );
}
