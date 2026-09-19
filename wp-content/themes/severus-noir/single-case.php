<?php
/**
 * A case study: the write-up with its client facts, the client's testimonial,
 * two more cases, and the closing call to action.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$fields      = severus_field( 'cases_fields' );
	$testimonial = severus_field( 'case_testimonial' );

	/* No lede: the short description is usually the write-up's first
	   paragraph, and it would read twice. */
	severus_component(
		'page-hero',
		array(
		)
	);
	?>
	<article <?php post_class( 'entry case' ); ?>>
		<div class="shell is-narrow">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry__art"><?php the_post_thumbnail( 'full' ); ?></figure>
			<?php endif; ?>

			<?php severus_component(
				'case-facts',
				array(
					'facts' => is_array( $fields ) ? ( $fields['case_info'] ?? array() ) : array(),
					'id'    => get_the_ID(),
				)
			); ?>

			<?php if ( '' !== trim( get_the_content() ) ) : ?>
				<div class="entry__body"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</article>
	<?php
	if ( is_array( $testimonial ) && ! empty( $testimonial['testimonial'] ) ) {
		severus_component(
			'voices',
			array(
				'reviews' => array( $testimonial['testimonial'] ),
				'label'   => $testimonial['subtitle'] ?? '',
				'title'   => ( $testimonial['title'] ?? '' ) ?: __( 'What our client says', 'severus-noir' ),
				'kicker'  => '',
				'text'    => '',
				'button'  => null,
			)
		);
	}

	$more = get_posts(
		array(
			'post_type'   => 'case',
			'numberposts' => 3,
			'orderby'     => 'rand',
			'exclude'     => array( get_the_ID() ),
			'fields'      => 'ids',
		)
	);

	severus_component(
		'results',
		array(
			'id'    => 'more-cases',
			'cases' => $more,
			'title' => __( 'More cases', 'severus-noir' ),
			'text'  => '',
		)
	);

	severus_component( 'callout', severus_get_started() );
endwhile;

get_footer();
