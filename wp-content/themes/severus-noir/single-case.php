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

	$fields      = get_field( 'cases_fields' );
	$testimonial = get_field( 'case_testimonial' );
	$sections    = get_field( 'case_sections' );

	/* No lede: the short description is usually the write-up's first
	   paragraph, and it would read twice. */
	severus_component(
		'case-hero',
		array(
			'facts' => is_array( $fields ) ? ( $fields['case_info'] ?? array() ) : array(),
			'id'    => get_the_ID(),
		)
	);
	?>
	<article <?php post_class( 'entry case' ); ?>>
		<?php if ( $sections ) : ?>
			<?php severus_component( 'case-sections', array( 'sections' => $sections ) ); ?>
		<?php elseif ( '' !== trim( get_the_content() ) ) : ?>
			<?php /* Not split into sections yet: the editor content, as before. */ ?>
			<div class="shell is-narrow">
				<div class="entry__body"><?php the_content(); ?></div>
			</div>
		<?php endif; ?>
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
