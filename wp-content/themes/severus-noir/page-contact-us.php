<?php
/**
 * Template Name: Contact
 *
 * The contact page: the copy from its fields beside the theme's form.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$contact = get_field( 'contact_form' );
	$contact = is_array( $contact ) ? $contact : array();

	severus_component(
		'talk',
		array(
			'id'       => 'contact',
			'heading'  => 'h1',
			'orbit'    => 'left',
			'label'    => $contact['under_title'] ?? '',
			'title'    => ( $contact['title'] ?? '' ) ?: get_the_title(),
			'subtitle' => $contact['subtitle'] ?? '',
			'text'     => $contact['text'] ?? '',
			'location' => (string) get_option( 'site_address' ),
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
endwhile;

get_footer();
