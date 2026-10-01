<?php
/**
 * Template Name: Industries
 *
 * Native page title/content and every published industry as a picture tile.
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
			'title'  => get_the_title(),
			'text'   => apply_filters( 'the_content', get_the_content() ),
			'center' => true,
		)
	);

	$industries = get_posts(
		array(
			'post_type'   => 'industry',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		)
	);

	severus_component(
		'tiles',
		array(
			'id'    => 'industries',
			'title' => '',
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

	severus_component( 'callout', severus_get_started() );
endwhile;

get_footer();
