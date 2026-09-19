<?php
/**
 * A service. Every section reads the fields the previous theme used, so the
 * content already in the database renders as it is.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$pair = static fn( $rows ) => array_map(
		static fn( $row ) => array( 'title' => $row['title'] ?? '', 'text' => $row['text'] ?? '' ),
		(array) $rows
	);

	severus_component(
		'page-hero',
		array(
			'label'  => severus_field( 'service_label' ),
			'title'  => severus_field( 'service_hero_title' ),
			'text'   => severus_field( 'service_description' ),
			'button' => severus_field( 'service_hero_button' ),
			'image'  => severus_image( severus_field( 'service_hero_image' ) ),
			'art'    => 'framed',
		)
	);

	severus_component(
		'brief',
		array(
			'id'    => 'what-it-is',
			'label' => severus_field( 'service_what_label' ),
			'title' => severus_field( 'service_what_title' ),
			'text'  => severus_field( 'service_what_description' ),
			'note'  => severus_field( 'service_what_note' ),
		)
	);

	severus_component(
		'points',
		array(
			'id'     => 'when',
			'label'  => severus_field( 'service_when_label' ),
			'title'  => severus_field( 'service_when_title' ),
			'text'   => severus_field( 'service_when_description' ),
			'items'  => $pair( severus_field( 'service_when_items' ) ),
			'layout' => 'cards',
		)
	);

	severus_component(
		'points',
		array(
			'id'     => 'approach',
			'label'  => severus_field( 'service_approach_label' ),
			'title'  => severus_field( 'service_approach_title' ),
			'text'   => severus_field( 'service_approach_description' ),
			'items'  => $pair( severus_field( 'service_approach_items' ) ),
			'layout' => 'list',
		)
	);

	severus_component(
		'gains',
		array(
			'id'    => 'what-you-get',
			'label' => severus_field( 'service_get_label' ),
			'title' => severus_field( 'service_get_title' ),
			'text'  => severus_field( 'service_get_description' ),
			'items' => $pair( severus_field( 'service_get_items' ) ),
		)
	);

	severus_component(
		'shift',
		array(
			'id'    => 'what-changes',
			'label' => severus_field( 'service_changes_label' ),
			'title' => severus_field( 'service_changes_title' ),
			'items' => $pair( severus_field( 'service_changes_items' ) ),
		)
	);

	severus_component(
		'services',
		array(
			'id'       => 'more-services',
			'services' => severus_service_siblings( get_the_ID() ),
			'label'    => severus_field( 'service_more_label' ),
			'title'    => severus_field( 'service_more_title' ),
			'text'     => severus_field( 'service_more_description' ),
			'button'   => null,
		)
	);

	severus_component(
		'faq',
		array(
			'label' => severus_field( 'service_faq_label' ),
			'title' => severus_field( 'service_faq_title' ),
			'items' => (array) severus_field( 'service_faq_items' ),
		)
	);

	severus_component(
		'callout',
		array(
			'title'  => severus_field( 'service_cta_title' ),
			'text'   => severus_field( 'service_cta_description' ),
			'button' => severus_field( 'service_cta_button' ),
		)
	);
endwhile;

get_footer();
