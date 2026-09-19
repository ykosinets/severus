<?php
/**
 * Template Name: About us
 *
 * About Severus: the statement, what we deliver, our values and the portfolio.
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
			'orbit'    => 'right',
			'title'    => severus_field( 'heading_h1' ),
			'subtitle' => severus_field( 'heading_h2' ),
			'text'     => severus_field( 'description' ),
		)
	);

	$services = severus_field( 'services-section' );

	if ( is_array( $services ) ) {
		severus_component(
			'tiles',
			array(
				'id'    => 'what-we-deliver',
				'label' => $services['subtitle'] ?? '',
				'title' => $services['title'] ?? '',
				'items' => array_map(
					static fn( $row ) => array(
						'title' => $row['title'] ?? '',
						'text'  => $row['description'] ?? '',
						'url'   => $row['link'] ?? '',
						'image' => severus_image( $row['image'] ?? null ),
					),
					(array) ( $services['services_list'] ?? array() )
				),
			)
		);
	}

	$values = severus_field( 'faq-section' );

	if ( is_array( $values ) ) {
		severus_component(
			'faq',
			array(
				'id'    => 'values',
				'orbit' => 'left',
				'title' => $values['title'] ?? '',
				'text'  => $values['description'] ?? '',
				'items' => (array) ( $values['faq_list'] ?? array() ),
			)
		);
	}

	severus_component(
		'results',
		array(
			'cases' => severus_field( 'case-list' ) ?: array(),
			'orbit' => 'right',
			'label' => severus_field( 'case-subtitle' ),
			'title' => severus_field( 'case-title' ),
			'text'  => '',
		)
	);

	if ( severus_field( 'show_get_started' ) ) {
		severus_component( 'callout', severus_get_started() );
	}

	if ( '' !== trim( get_the_content() ) ) :
		?>
		<article class="section entry">
			<div class="shell is-narrow">
				<div class="entry__body"><?php the_content(); ?></div>
			</div>
		</article>
		<?php
	endif;
endwhile;

get_footer();
