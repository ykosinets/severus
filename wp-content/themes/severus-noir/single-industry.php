<?php
/**
 * An industry: the pitch, then case studies from its matching category.
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

	$cases_link = get_field( 'see_all_construction_case' );
	$case_category = get_term_by( 'slug', get_post_field( 'post_name', get_the_ID() ), 'case_category' );
	$industry_cases = $case_category instanceof WP_Term
		? get_posts(
			array(
				'post_type'      => 'case',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'fields'         => 'ids',
				'orderby'        => array( 'date' => 'DESC', 'ID' => 'DESC' ),
				'tax_query'      => array(
					array(
						'taxonomy'         => 'case_category',
						'field'            => 'term_id',
						'terms'            => $case_category->term_id,
						'include_children' => false,
					),
				),
			)
		)
		: array();

	if ( is_array( $cases_link ) ) {
		$cases_url = $case_category instanceof WP_Term
			? get_term_link( $case_category )
			: get_post_type_archive_link( 'case' );

		if ( $cases_url && ! is_wp_error( $cases_url ) ) {
			$cases_link['url'] = $cases_url . '#case-studies';
		}
	}

	severus_component(
		'results',
		array(
			'id'    => 'case-studies',
			'cases' => $industry_cases,
			'label' => get_field( 'cs_label' ),
			'title' => get_field( 'cs_title' ),
			'text'  => get_field( 'cs_description' ),
			'more'  => $cases_link,
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
