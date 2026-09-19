<?php
/**
 * Field group: Service card — list and background
 *
 * Written by hand rather than exported: these fields were added by the theme,
 * never by the ACF admin, so this file has always been their definition.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

return array(
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
);
