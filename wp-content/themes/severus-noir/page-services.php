<?php
/**
 * Services page: native page content and all published services.
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

	severus_component(
		'services',
		array(
			'services' => get_posts(
				array(
					'post_type'      => 'service',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				)
			),
			'feature' => true,
			'label'   => '',
			'title'   => '',
			'text'    => '',
			'button'  => false,
		)
	);

endwhile;

get_footer();
