<?php
/**
 * A page: its title, the block editor content, then whichever sections the
 * page's ACF fields fill in — the same set the previous theme's page template
 * offered (services, cases, reviews, articles, contact).
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$page_title = get_the_title();

	/* A section titled like the page would just repeat the h1. */
	$heading = static fn( $title ) => ( $title && 0 !== strcasecmp( wp_strip_all_tags( (string) $title ), $page_title ) ) ? $title : '';

	/* On the services listing the page copy is a short intro: it goes into
	   the hero, centred under the title, instead of an article below. */
	$intro = get_field( 'service-title' ) && ! has_post_thumbnail();

	severus_component(
		'page-hero',
		array(
			'text'   => $intro ? apply_filters( 'the_content', get_the_content() ) : '',
			'center' => $intro,
		)
	);

	if ( ! $intro && ( has_post_thumbnail() || '' !== trim( get_the_content() ) ) ) :
		?>
		<article class="entry page-body">
			<div class="shell is-narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry__art"><?php the_post_thumbnail( 'full' ); ?></figure>
				<?php endif; ?>

				<div class="entry__body">
					<?php
					the_content();
					wp_link_pages( array( 'before' => '<nav class="entry__pages">', 'after' => '</nav>' ) );
					?>
				</div>
			</div>
		</article>
		<?php
	endif;

	/* A page that gives the services section a heading gets the section, with
	   the service tree in it. */
	if ( get_field( 'service-title' ) ) {
		severus_component(
			'services',
			array(
				'feature' => true,
				'label'   => get_field( 'service-subtitle' ),
				'title'   => $heading( get_field( 'service-title' ) ),
				'text'    => '',
				'button'  => null,
			)
		);
	}

	if ( get_field( 'case-list' ) ) {
		severus_component(
			'results',
			array(
				'cases' => get_field( 'case-list' ),
				'label' => get_field( 'case-subtitle' ),
				'title' => $heading( get_field( 'case-title' ) ),
				'text'  => '',
			)
		);

		$group = get_field( 'home_group_fields' );

		if ( is_array( $group ) && ! empty( $group['show_get_started'] ) ) {
			severus_component( 'callout', severus_get_started() );
		}
	}

	if ( get_field( 'review_list' ) ) {
		severus_component(
			'voices',
			array(
				'reviews' => get_field( 'review_list' ),
				'label'   => get_field( 'review-subtitle' ),
				'title'   => $heading( get_field( 'review-title' ) ),
				'kicker'  => '',
				'text'    => '',
				'button'  => null,
			)
		);
	}

	if ( get_field( 'insights_list' ) ) {
		severus_component(
			'journal',
			array(
				'posts'  => get_field( 'insights_list' ),
				'label'  => get_field( 'insights-subtitle' ),
				'title'  => $heading( get_field( 'insights-title' ) ),
				'button' => null,
			)
		);
	}

	$contact = get_field( 'contact_form' );

	if ( is_array( $contact ) && ! empty( $contact['title'] ) ) {
		severus_component(
			'talk',
			array(
				'id'       => 'contact',
				'label'    => $contact['under_title'] ?? '',
				'title'    => $contact['title'],
				'subtitle' => $contact['subtitle'] ?? '',
				'text'     => $contact['text'] ?? '',
				'location' => (string) get_option( 'site_address' ),
			)
		);
	}
endwhile;

get_footer();
