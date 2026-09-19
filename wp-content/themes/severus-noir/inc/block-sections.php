<?php
/**
 * What each section block holds, in one table.
 *
 * A section block is its component plus a list of attributes. The table below
 * says which attribute feeds which key of the component's $data, and how the
 * stored value is turned into what the component expects — line breaks put
 * back into a textarea, an attachment id left as an id, a link kept as the
 * array severus_button() takes. Rows are child blocks, listed the same way.
 *
 * Both sides read this: severus_render_section_data() renders from it, and
 * the editor is generated from its twin in src/editor/schema.js. Adding a
 * field to a section means an entry here, an entry there, and nothing else.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * The sections, keyed by block name without the severus/ prefix.
 *
 * component  which components/<name>/ renders it (defaults to the block name)
 * attrs      attribute => data key, or attribute => array( data, format )
 * rows       child block => array( data key, map of attribute => row key )
 *
 * Formats: 'lines' puts line breaks back, the way the textarea fields these
 * replace were formatted; everything else is passed through, because the
 * components already take ids for images, arrays for links and ids for posts.
 *
 * @return array<string, array<string, mixed>>
 */
function severus_block_sections(): array {
	return array(
		'turn'     => array(
			'attrs' => array( 'title' => array( 'title', 'lines' ) ),
		),
		'services' => array(
			'attrs' => array(
				'title'  => 'title',
				'text'   => array( 'text', 'lines' ),
				'button' => 'button',
			),
		),
		'results'  => array(
			'attrs' => array(
				'title' => array( 'title', 'lines' ),
				'text'  => array( 'text', 'lines' ),
				'cases' => 'cases',
			),
		),
		'voices'   => array(
			'attrs' => array(
				'title'   => array( 'title', 'lines' ),
				'kicker'  => 'kicker',
				'text'    => 'text',
				'button'  => 'button',
				'reviews' => 'reviews',
			),
		),
		'journal'  => array(
			'attrs' => array(
				'title'  => array( 'title', 'lines' ),
				'button' => 'button',
				'posts'  => 'posts',
			),
		),
		'talk'     => array(
			'attrs' => array(
				'title' => 'title',
				'text'  => array( 'text', 'lines' ),
				'logoId' => 'logo',
			),
		),
		'faq'      => array(
			'attrs' => array( 'title' => array( 'title', 'lines' ) ),
			'rows'  => array(
				'severus/faq-item' => array(
					'data' => 'items',
					'map'  => array(
						'question' => 'question',
						'answer'   => array( 'answer', 'lines' ),
					),
				),
			),
		),
		'familiar' => array(
			'attrs' => array(
				'title' => 'title',
				'intro' => array( 'intro', 'lines' ),
			),
			'rows'  => array(
				'severus/familiar-card' => array(
					'data' => 'cards',
					'map'  => array(
						'imageId' => 'card_image',
						'heading' => array( 'card_heading', 'lines' ),
						'text'    => array( 'card_text', 'lines' ),
					),
				),
			),
		),
		'method'   => array(
			'attrs' => array(
				'title'       => array( 'title', 'lines' ),
				'subtitle'    => array( 'subtitle', 'lines' ),
				'description' => array( 'description', 'lines' ),
			),
			'rows'  => array(
				'severus/method-step' => array(
					'data' => 'steps',
					'map'  => array( 'text' => array( 'text', 'lines' ) ),
				),
			),
		),
		'impact'   => array(
			'attrs' => array(
				'title'    => 'title',
				'subtitle' => array( 'subtitle', 'lines' ),
			),
			'rows'  => array(
				'severus/impact-slide' => array(
					'data' => 'slides',
					'map'  => array(
						'title'   => 'title',
						'text'    => array( 'text', 'lines' ),
						'imageId' => 'image',
						'button'  => 'button',
					),
				),
			),
		),
		'people'   => array(
			'attrs' => array(
				'title' => 'title',
				'intro' => array( 'intro', 'lines' ),
			),
			'rows'  => array(
				'severus/person' => array(
					'data' => 'experts',
					'map'  => array(
						'photoId'  => 'photo',
						'name'     => 'name',
						'role'     => 'role',
						'quote'    => array( 'quote', 'lines' ),
						'overlay'  => 'overlay',
						'linkedin' => 'linkedin',
					),
				),
			),
		),
		'trust'    => array(
			'attrs' => array(
				'title'  => 'title',
				'text'   => 'text',
				'logoId' => 'logo',
				'button' => 'button',
			),
			'rows'  => array(
				'severus/trust-figure' => array(
					'data' => 'figures',
					'map'  => array(
						'number' => 'number',
						'text'   => array( 'text', 'lines' ),
					),
				),
				'severus/trust-note'   => array(
					'data' => 'notes',
					'map'  => array( 'text' => array( 'text', 'lines' ) ),
				),
			),
		),
		'hero'     => array(
			'attrs' => array(
				'title'     => array( 'title', 'lines' ),
				'intro'     => array( 'intro', 'lines' ),
				'videoId'   => 'video',
				'posterId'  => 'poster',
				'duration'  => 'duration',
				'primary'   => 'primary',
				'secondary' => 'secondary',
			),
			'rows'  => array(
				'severus/hero-stat' => array(
					'data' => 'stats',
					'map'  => array(
						'iconId' => 'icon',
						'link'   => 'link',
						'text'   => array( 'text', 'lines' ),
					),
				),
			),
		),
	);
}

/**
 * The $data a section block hands its component.
 *
 * @param string               $section    Section key.
 * @param array<string, mixed> $attributes Block attributes.
 * @param array<int, mixed>    $children   Parsed inner blocks.
 * @return array<string, mixed>
 */
function severus_render_section_data( string $section, array $attributes, array $children = array() ): array {
	$schema = severus_block_sections()[ $section ] ?? array();
	$data   = array();

	foreach ( (array) ( $schema['attrs'] ?? array() ) as $attribute => $target ) {
		if ( ! array_key_exists( $attribute, $attributes ) ) {
			continue;
		}

		[ $key, $format ] = is_array( $target ) ? $target + array( 1 => '' ) : array( $target, '' );

		$data[ $key ] = severus_block_value( $attributes[ $attribute ], (string) $format );
	}

	foreach ( (array) ( $schema['rows'] ?? array() ) as $child => $row ) {
		$data[ $row['data'] ] = array();
	}

	foreach ( $children as $child ) {
		$name = (string) ( $child['blockName'] ?? '' );
		$row  = $schema['rows'][ $name ] ?? null;

		if ( ! $row ) {
			continue;
		}

		$values = array();

		foreach ( (array) $row['map'] as $attribute => $target ) {
			[ $key, $format ] = is_array( $target ) ? $target + array( 1 => '' ) : array( $target, '' );

			$values[ $key ] = severus_block_value( $child['attrs'][ $attribute ] ?? null, (string) $format );
		}

		$data[ $row['data'] ][] = $values;
	}

	return $data;
}

/**
 * One attribute, as the component wants it.
 *
 * @param mixed $value Stored attribute.
 * @return mixed
 */
function severus_block_value( $value, string $format ) {
	if ( 'lines' === $format ) {
		return nl2br( (string) $value );
	}

	return $value;
}
