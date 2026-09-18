<?php
/**
 * Client testimonials.
 *
 * $data (all optional; the front page fields are the defaults):
 *   reviews                   post ids or objects
 *   label, title, kicker, text, button   the side column
 */
defined( 'ABSPATH' ) || exit;

$reviews = severus_ids( $data['reviews'] ?? get_field( 'review_list' ) );

if ( ! $reviews ) {
	return;
}

$label  = $data['label'] ?? '';
$title  = $data['title'] ?? get_field( 'testimonials_title' );
$kicker = $data['kicker'] ?? get_field( 'testimonials_subheading' );
$text   = $data['text'] ?? get_field( 'testimonials_text' );
$button = $data['button'] ?? get_field( 'testimonials_button' );
?>
<section class="section section--cool voices">
	<div class="shell voices__in">

		<div class="voices__side">
			<div class="voices__stars" aria-label="<?php esc_attr_e( 'Rated 5 out of 5', 'severus-noir' ); ?>">★★★★★</div>

			<?php if ( $label ) : ?>
				<p class="eyebrow"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h2 class="voices__title is-display"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $kicker ) : ?>
				<h3 class="voices__kicker"><?php echo wp_kses_post( $kicker ); ?></h3>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="voices__text"><?php echo wp_kses_post( $text ); ?></p>
			<?php endif; ?>

			<?php severus_button( $button, 'btn btn--solid' ); ?>
		</div>

		<div class="voices__rail">
			<?php
			foreach ( $reviews as $id ) :
				$review = get_field( 'reviews', $id );
				$role   = is_array( $review ) ? ( $review['profession'] ?? '' ) : '';
				?>
				<figure class="quote reveal">
					<blockquote><?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $id ) ) ); ?></blockquote>
					<figcaption>
						<?php echo get_the_post_thumbnail( $id, array( 70, 70 ), array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
						<span>
							<b><?php echo esc_html( get_the_title( $id ) ); ?></b>
							<?php if ( $role ) : ?><i><?php echo esc_html( $role ); ?></i><?php endif; ?>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

	</div>
</section>
