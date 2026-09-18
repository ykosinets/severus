<?php
/**
 * Template Name: Industries
 *
 * Every published industry as a picture tile.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	severus_component(
		'page-hero',
		array(
			'text'  => get_field( 'industries_text' ),
		)
	);

	$industries = get_posts(
		array(
			'post_type'   => 'industry',
			'numberposts' => -1,
			'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		)
	);

	severus_component(
		'tiles',
		array(
			'id'    => 'industries',
			'title' => get_field( 'industries_section_title' ),
			'items' => array_map(
				static function ( WP_Post $industry ): array {
					$image = get_field( 'industry_hero_image', $industry->ID );

					return array(
						'title' => get_the_title( $industry ),
						'text'  => get_field( 'industry_subtitle', $industry->ID ),
						'url'   => get_permalink( $industry ),
						'image' => has_post_thumbnail( $industry )
							? severus_image( get_post_thumbnail_id( $industry ) )
							: severus_image( $image ),
					);
				},
				$industries
			),
		)
	);

	$group = get_field( 'home_group_fields' );

	if ( is_array( $group ) && ! empty( $group['show_get_started'] ) ) {
		severus_component( 'callout', severus_get_started() );
	}
endwhile;

get_footer();
