<?php
/**
 * Field group: Booking dialog
 *
 * Written by hand rather than exported: these fields were added by the theme,
 * never by the ACF admin, so this file has always been their definition.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

return array(
	'key'                   => 'group_severus_booking',
	'title'                 => __( 'Booking dialog', 'severus-noir' ),
	'fields'                => array(
		array(
			'key'           => 'field_severus_booking_photo',
			'label'         => __( 'Host photo', 'severus-noir' ),
			'name'          => 'booking_photo',
			'type'          => 'image',
			'instructions'  => __( 'Shown beside the Calendly calendar when a booking link is opened on the site.', 'severus-noir' ),
			'return_format' => 'id',
			'preview_size'  => 'thumbnail',
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
);
