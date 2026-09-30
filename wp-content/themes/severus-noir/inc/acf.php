<?php
/**
 * ACF fields the theme adds on top of the groups stored in the database.
 *
 * Registered in code so they ship with the theme: a deploy brings them to
 * staging and production without moving the field group rows. They show in
 * the editor like any other group; their definitions are edited here.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

function severus_register_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* A case study's own fields in one group, three tabs down the left:
	   Content (the write-up, built from sections), Case info and
	   Testimonials. The last two keep the keys of the "Cases page" group
	   stored in the database, so the values already saved load as they are;
	   that group is kept out of the editor below. Every text in Content is
	   the visual editor, and a section with nothing in it is not rendered. */
	$case_text = static fn( string $key, string $label, string $name = 'content', string $toolbar = 'full' ): array => array(
		'key'          => $key,
		'label'        => $label,
		'name'         => $name,
		'type'         => 'wysiwyg',
		'tabs'         => 'all',
		'toolbar'      => $toolbar,
		'media_upload' => 0,
		'delay'        => 0,
	);
	$case_tab  = static fn( string $key, string $label ): array => array(
		'key'       => $key,
		'label'     => $label,
		'name'      => '',
		'type'      => 'tab',
		'placement' => 'left',
		'endpoint'  => 0,
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_severus_case',
			'title'                 => __( 'Case study', 'severus-noir' ),
			'fields'                => array(
				$case_tab( 'field_severus_case_tab_content', __( 'Content', 'severus-noir' ) ),
				array(
					'key'          => 'field_severus_case_sections',
					'label'        => __( 'Sections', 'severus-noir' ),
					'name'         => 'case_sections',
					'type'         => 'flexible_content',
					'instructions' => __( 'The write-up, a section at a time. While this is empty the page shows the content editor instead.', 'severus-noir' ),
					'button_label' => __( 'Add section', 'severus-noir' ),
					'layouts'      => array(
						'layout_severus_case_columns' => array(
							'key'        => 'layout_severus_case_columns',
							'name'       => 'columns',
							'label'      => __( 'Content (2 columns)', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array_merge( $case_text( 'field_severus_case_columns_left', __( 'Left', 'severus-noir' ), 'left' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
								array_merge( $case_text( 'field_severus_case_columns_right', __( 'Right', 'severus-noir' ), 'right' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
							),
						),
						'layout_severus_case_text'    => array(
							'key'        => 'layout_severus_case_text',
							'name'       => 'text',
							'label'      => __( 'Content (full width)', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								$case_text( 'field_severus_case_text_content', __( 'Text', 'severus-noir' ) ),
							),
						),
						'layout_severus_case_divider' => array(
							'key'        => 'layout_severus_case_divider',
							'name'       => 'divider',
							'label'      => __( 'Divider', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(),
						),
						'layout_severus_case_steps'   => array(
							'key'        => 'layout_severus_case_steps',
							'name'       => 'steps',
							'label'      => __( 'Steps', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'          => 'field_severus_case_steps_items',
									'label'        => __( 'Steps', 'severus-noir' ),
									'name'         => 'items',
									'type'         => 'repeater',
									'instructions' => __( 'One row each: a bullet (01, Step 1, Q3 — left empty it counts up by itself) and its text.', 'severus-noir' ),
									'layout'       => 'block',
									'button_label' => __( 'Add step', 'severus-noir' ),
									'sub_fields'   => array(
										array(
											'key'     => 'field_severus_case_steps_bullet',
											'label'   => __( 'Bullet', 'severus-noir' ),
											'name'    => 'bullet',
											'type'    => 'text',
											'wrapper' => array( 'width' => '20' ),
										),
										array_merge( $case_text( 'field_severus_case_steps_content', __( 'Text', 'severus-noir' ), 'content', 'basic' ), array( 'wrapper' => array( 'width' => '80' ) ) ),
									),
								),
							),
						),
						'layout_severus_case_button'  => array(
							'key'        => 'layout_severus_case_button',
							'name'       => 'button',
							'label'      => __( 'Button', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'           => 'field_severus_case_button_link',
									'label'         => __( 'Link', 'severus-noir' ),
									'name'          => 'link',
									'type'          => 'link',
									'return_format' => 'array',
								),
								array(
									'key'           => 'field_severus_case_button_align',
									'label'         => __( 'Alignment', 'severus-noir' ),
									'name'          => 'align',
									'type'          => 'button_group',
									'choices'       => array(
										'left'   => __( 'Left', 'severus-noir' ),
										'center' => __( 'Centre', 'severus-noir' ),
										'right'  => __( 'Right', 'severus-noir' ),
									),
									'default_value' => 'left',
									'return_format' => 'value',
								),
							),
						),
						'layout_severus_case_ticks' => array(
							'key' => 'layout_severus_case_ticks', 'name' => 'ticks', 'label' => __( 'Ticks', 'severus-noir' ), 'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_severus_case_ticks_title', 'label' => __( 'Title', 'severus-noir' ), 'name' => 'title', 'type' => 'text' ),
								array( 'key' => 'field_severus_case_ticks_items', 'label' => __( 'Items', 'severus-noir' ), 'name' => 'items', 'type' => 'repeater', 'sub_fields' => array( array( 'key' => 'field_severus_case_ticks_item_title', 'label' => __( 'Title', 'severus-noir' ), 'name' => 'title', 'type' => 'text' ), $case_text( 'field_severus_case_ticks_item_text', __( 'Text', 'severus-noir' ), 'text', 'basic' ) ) ),
							),
						),
						'layout_severus_case_table' => array(
							'key' => 'layout_severus_case_table', 'name' => 'table', 'label' => __( 'Table', 'severus-noir' ), 'display' => 'block',
							'sub_fields' => array( array( 'key' => 'field_severus_case_table_title', 'label' => __( 'Title', 'severus-noir' ), 'name' => 'title', 'type' => 'text' ), $case_text( 'field_severus_case_table_content', __( 'Table and note', 'severus-noir' ) ) ),
						),
						'layout_severus_case_media' => array(
							'key' => 'layout_severus_case_media', 'name' => 'media', 'label' => __( 'Media', 'severus-noir' ), 'display' => 'block',
							'sub_fields' => array( $case_text( 'field_severus_case_media_title', __( 'Title', 'severus-noir' ), 'title' ), array( 'key' => 'field_severus_case_media_asset', 'label' => __( 'Media', 'severus-noir' ), 'name' => 'media', 'type' => 'image', 'return_format' => 'array' ), array( 'key' => 'field_severus_case_media_align', 'label' => __( 'Alignment', 'severus-noir' ), 'name' => 'align', 'type' => 'button_group', 'choices' => array( 'left' => __( 'Left', 'severus-noir' ), 'right' => __( 'Right', 'severus-noir' ), 'center' => __( 'Centre', 'severus-noir' ) ), 'default_value' => 'center' ) ),
						),
					),
				),

				$case_tab( 'field_662380a12b74b', __( 'Case info', 'severus-noir' ) ),
				array(
					'key'        => 'field_661786629fd77',
					'label'      => __( 'Cases Fields', 'severus-noir' ),
					'name'       => 'cases_fields',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						array(
							'key'          => 'field_6617870b9fd7a',
							'label'        => __( 'Short description', 'severus-noir' ),
							'name'         => 'short_description',
							'type'         => 'textarea',
							'instructions' => __( 'The summary on case cards across the site.', 'severus-noir' ),
						),
						array(
							'key'          => 'field_661786b79fd78',
							'label'        => __( 'Advantages list', 'severus-noir' ),
							'name'         => 'advantages_list',
							'type'         => 'repeater',
							'layout'       => 'table',
							'min'          => 0,
							'max'          => 3,
							'button_label' => __( 'Add Row', 'severus-noir' ),
							'sub_fields'   => array(
								array(
									'key'   => 'field_661786db9fd79',
									'label' => __( 'Title', 'severus-noir' ),
									'name'  => 'title',
									'type'  => 'text',
								),
							),
						),
						array(
							'key'        => 'field_661787469fd7b',
							'label'      => __( 'Case info', 'severus-noir' ),
							'name'       => 'case_info',
							'type'       => 'group',
							'layout'     => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'field_661787a79fd7c',
									'label' => __( 'Client', 'severus-noir' ),
									'name'  => 'client',
									'type'  => 'text',
								),
								array(
									'key'   => 'field_661787b59fd7d',
									'label' => __( 'Industry', 'severus-noir' ),
									'name'  => 'industry',
									'type'  => 'text',
								),
								array(
									'key'   => 'field_661787c69fd7e',
									'label' => __( 'Country', 'severus-noir' ),
									'name'  => 'country',
									'type'  => 'text',
								),
							),
						),
					),
				),

				$case_tab( 'field_662380bb2b74c', __( 'Testimonials', 'severus-noir' ) ),
				array(
					'key'        => 'field_6623818fa36de',
					'label'      => __( 'Case testimonial', 'severus-noir' ),
					'name'       => 'case_testimonial',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						array(
							'key'   => 'field_662381c9a36df',
							'label' => __( 'Subtitle', 'severus-noir' ),
							'name'  => 'subtitle',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_662381d3a36e0',
							'label' => __( 'Title', 'severus-noir' ),
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'           => 'field_662381dea36e1',
							'label'         => __( 'Testimonial', 'severus-noir' ),
							'name'          => 'testimonial',
							'type'          => 'post_object',
							'post_type'     => array( 'review' ),
							'return_format' => 'object',
							'multiple'      => 0,
							'allow_null'    => 0,
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'case',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'show_in_rest'          => 0,
		)
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_severus_service_card_extra',
			'title'                 => __( 'Service card — list and background', 'severus-noir' ),
			'fields'                => array(
				array(
					'key'           => 'field_severus_service_card_image',
					'label'         => __( 'Card background', 'severus-noir' ),
					'name'          => 'service_card_image',
					'type'          => 'image',
					'instructions'  => __( 'Sits behind the top of the service card and fades out towards the text. A wide, dark image works best.', 'severus-noir' ),
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
					'mime_types'    => 'jpg, jpeg, png, webp, avif',
				),
				array(
					'key'          => 'field_severus_service_card_points',
					'label'        => __( 'Card list', 'severus-noir' ),
					'name'         => 'service_card_points',
					'type'         => 'repeater',
					'instructions' => __( 'The ticked lines on the service card. Four read best.', 'severus-noir' ),
					'layout'       => 'table',
					'min'          => 0,
					'max'          => 6,
					'button_label' => __( 'Add line', 'severus-noir' ),
					'sub_fields'   => array(
						array(
							'key'      => 'field_severus_service_card_point',
							'label'    => __( 'Line', 'severus-noir' ),
							'name'     => 'text',
							'type'     => 'text',
							'required' => 1,
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'menu_order'            => 1,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'show_in_rest'          => 0,
		)
	);


	acf_add_local_field_group(
		array(
			'key'                   => 'group_severus_booking',
			'title'                 => __( 'Booking dialog', 'severus-noir' ),
			'fields'                => array(
				array(
					'key'          => 'field_severus_booking_photo',
					'label'        => __( 'Host photo', 'severus-noir' ),
					'name'         => 'booking_photo',
					'type'         => 'image',
					'instructions' => __( 'Shown beside the Calendly calendar when a booking link is opened on the site.', 'severus-noir' ),
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
				),
				array(
					'key'           => 'field_severus_booking_host',
					'label'         => __( 'Host name', 'severus-noir' ),
					'name'          => 'booking_host',
					'type'          => 'text',
					'default_value' => 'Olena Bochulia',
				),
				array(
					'key'           => 'field_severus_booking_title',
					'label'         => __( 'Call title', 'severus-noir' ),
					'name'          => 'booking_title',
					'type'          => 'text',
					'default_value' => 'Severus | Helping business owners to scale and grow',
				),
				array(
					'key'           => 'field_severus_booking_duration',
					'label'         => __( 'Duration', 'severus-noir' ),
					'name'          => 'booking_duration',
					'type'          => 'text',
					'default_value' => '30 min',
				),
				array(
					'key'           => 'field_severus_booking_note',
					'label'         => __( 'Note', 'severus-noir' ),
					'name'          => 'booking_note',
					'type'          => 'textarea',
					'rows'          => 3,
					'default_value' => 'Web conferencing details provided upon confirmation.',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'theme-settings',
					),
				),
			),
			'menu_order'            => 20,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'show_in_rest'          => 0,
		)
	);
}
add_action( 'acf/include_fields', 'severus_register_fields' );

/**
 * The free-text "Industry" line in the case info group is replaced by the
 * case_category taxonomy. The group lives in the database, so the field is
 * hidden from the editor here rather than deleted there: it disappears on
 * every environment the theme is deployed to, and the old values stay in
 * post meta until the field is removed from the group in ACF.
 */
add_filter( 'acf/prepare_field/key=field_661787b59fd7d', '__return_false' );

/**
 * "Services List" on a service picked the other services shown under it, but
 * that section follows the service tree (severus_service_siblings()), so the
 * picks were never shown. Hidden the same way, for the same reason.
 */
add_filter( 'acf/prepare_field/key=field_6a6c6832ee144', '__return_false' );

/**
 * The "Cases page" group stored in the database now lives in the Case study
 * group above, under the same field keys. Leaving the stored copy out of the
 * editor keeps its fields from showing twice; its row stays in the database
 * and can be deleted in ACF once every environment runs this code.
 */
add_filter(
	'acf/get_field_groups',
	static fn( array $groups ): array => array_values( array_filter( $groups, static fn( $group ): bool => 'group_66178661ca047' !== ( $group['key'] ?? '' ) ) )
);
