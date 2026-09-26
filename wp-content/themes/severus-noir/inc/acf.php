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

	/* Case write-ups are built from sections. Every text field is the visual
	   editor; a section with nothing in it is not rendered. */
	$case_text = static fn( string $key, string $label = '' ): array => array(
		'key'          => $key,
		'label'        => $label ?: __( 'Content', 'severus-noir' ),
		'name'         => 'content',
		'type'         => 'wysiwyg',
		'tabs'         => 'all',
		'toolbar'      => 'full',
		'media_upload' => 0,
		'delay'        => 1,
	);
	$case_image = static fn( string $key ): array => array(
		'key'           => $key,
		'label'         => __( 'Image', 'severus-noir' ),
		'name'          => 'image',
		'type'          => 'image',
		'return_format' => 'id',
		'preview_size'  => 'medium',
		'library'       => 'all',
		'mime_types'    => 'jpg, jpeg, png, webp, avif, svg',
	);
	$case_reverse = static fn( string $key, string $label ): array => array(
		'key'           => $key,
		'label'         => __( 'Reverse', 'severus-noir' ),
		'name'          => 'is_reverse',
		'type'          => 'true_false',
		'message'       => $label,
		'ui'            => 1,
		'default_value' => 0,
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_severus_case_sections',
			'title'                 => __( 'Case sections', 'severus-noir' ),
			'fields'                => array(
				array(
					'key'          => 'field_severus_case_sections',
					'label'        => __( 'Sections', 'severus-noir' ),
					'name'         => 'case_sections',
					'type'         => 'flexible_content',
					'instructions' => __( 'The write-up, a section at a time, with a line between sections. While this is empty the page shows the content editor instead.', 'severus-noir' ),
					'button_label' => __( 'Add section', 'severus-noir' ),
					'layouts'      => array(
						'layout_severus_case_title_text'  => array(
							'key'        => 'layout_severus_case_title_text',
							'name'       => 'title_text',
							'label'      => __( 'Title + text', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'field_severus_case_tt_title',
									'label' => __( 'Title', 'severus-noir' ),
									'name'  => 'title',
									'type'  => 'text',
								),
								$case_text( 'field_severus_case_tt_content' ),
								$case_reverse( 'field_severus_case_tt_reverse', __( 'Text on the left, title on the right', 'severus-noir' ) ),
							),
						),
						'layout_severus_case_media_text'  => array(
							'key'        => 'layout_severus_case_media_text',
							'name'       => 'media_text',
							'label'      => __( 'Image + text', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								$case_image( 'field_severus_case_mt_image' ),
								$case_text( 'field_severus_case_mt_content' ),
								$case_reverse( 'field_severus_case_mt_reverse', __( 'Text on the left, image on the right', 'severus-noir' ) ),
							),
						),
						'layout_severus_case_text'        => array(
							'key'        => 'layout_severus_case_text',
							'name'       => 'text',
							'label'      => __( 'Text, full width', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								$case_text( 'field_severus_case_text_content' ),
							),
						),
						'layout_severus_case_image'       => array(
							'key'        => 'layout_severus_case_image',
							'name'       => 'image',
							'label'      => __( 'Image', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								$case_image( 'field_severus_case_image_image' ),
								array(
									'key'           => 'field_severus_case_image_align',
									'label'         => __( 'Alignment', 'severus-noir' ),
									'name'          => 'align',
									'type'          => 'button_group',
									'choices'       => array(
										'full'   => __( 'Full width', 'severus-noir' ),
										'center' => __( 'Centre', 'severus-noir' ),
										'left'   => __( 'Left', 'severus-noir' ),
										'right'  => __( 'Right', 'severus-noir' ),
									),
									'default_value' => 'full',
									'return_format' => 'value',
								),
							),
						),
						'layout_severus_case_cards'       => array(
							'key'        => 'layout_severus_case_cards',
							'name'       => 'cards',
							'label'      => __( 'Title + text + cards', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'field_severus_case_cards_title',
									'label' => __( 'Title', 'severus-noir' ),
									'name'  => 'title',
									'type'  => 'text',
								),
								$case_text( 'field_severus_case_cards_content' ),
								array(
									'key'          => 'field_severus_case_cards_items',
									'label'        => __( 'Cards', 'severus-noir' ),
									'name'         => 'cards',
									'type'         => 'repeater',
									'instructions' => __( 'Three to a row; more wrap onto new rows.', 'severus-noir' ),
									'layout'       => 'block',
									'button_label' => __( 'Add card', 'severus-noir' ),
									'sub_fields'   => array(
										$case_text( 'field_severus_case_card_content', __( 'Card', 'severus-noir' ) ),
									),
								),
							),
						),
						'layout_severus_case_related'     => array(
							'key'        => 'layout_severus_case_related',
							'name'       => 'related',
							'label'      => __( 'Related', 'severus-noir' ),
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'         => 'field_severus_case_related_title',
									'label'       => __( 'Title', 'severus-noir' ),
									'name'        => 'title',
									'type'        => 'text',
									'placeholder' => __( 'Related', 'severus-noir' ),
								),
								array(
									'key'           => 'field_severus_case_related_items',
									'label'         => __( 'Items', 'severus-noir' ),
									'name'          => 'items',
									'type'          => 'relationship',
									'instructions'  => __( 'Services, industries, cases or articles, shown as cards three to a row in this order.', 'severus-noir' ),
									'post_type'     => array( 'service', 'industry', 'case', 'post' ),
									'post_status'   => array( 'publish' ),
									'filters'       => array( 'search', 'post_type' ),
									'return_format' => 'id',
								),
							),
						),
						'layout_severus_case_button'      => array(
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
