<?php
/**
 * An industry: the pitch, then the case studies picked for it.
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
			'label'    => get_field( 'industry_label' ),
			'title'    => get_field( 'industry_hero_title' ),
			'subtitle' => get_field( 'industry_subtitle' ),
			'text'     => get_field( 'industry_description' ),
			'button'   => get_field( 'industry_hero_button' ),
			'image'    => severus_image( get_field( 'industry_hero_image' ) ),
		)
	);

	severus_component(
		'results',
		array(
			'id'    => 'case-studies',
			'cases' => get_field( 'cs_cases_list' ) ?: array(),
			'label' => get_field( 'cs_label' ),
			'title' => get_field( 'cs_title' ),
			'text'  => get_field( 'cs_description' ),
			'more'  => get_field( 'see_all_construction_case' ),
		)
	);

	if ( '' !== trim( get_the_content() ) ) :
		?>
		<article class="section entry">
			<div class="shell is-narrow">
				<div class="entry__body"><?php the_content(); ?></div>
			</div>
		</article>
		<?php
	endif;

	severus_component( 'callout', severus_get_started() );
endwhile;

get_footer();
