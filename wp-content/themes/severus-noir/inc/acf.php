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
