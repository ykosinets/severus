<?php
/**
 * "Sound Familiar?" — a stack of slabs that pin and shrink under the next one.
 *
 * $data (all optional; the page's fields are the defaults):
 *   title, intro  the header
 *   cards         rows of card_image (attachment id or array), card_heading,
 *                 card_text — what the severus/familiar block passes in
 */
defined( 'ABSPATH' ) || exit;

$cards = isset( $data['cards'] ) ? (array) $data['cards'] : severus_rows( 'sound_familiar_cards' );

if ( ! $cards ) {
	return;
}

$title = $data['title'] ?? severus_field( 'sound_familiar_title' );
$intro = $data['intro'] ?? severus_field( 'sound_familiar_intro' );
?>
<section class="section section--cool is-narrow familiar" id="sound-familiar">
	<div class="shell">

		<?php /* Pinned while the slabs scroll, so no .reveal — a view() timeline
			stalls on a sticky element. */ ?>
		<header class="lead familiar__lead" data-stack-lead>
			<?php if ( $title ) : ?>
				<h2 class="lead__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p class="lead__text"><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="stack" data-stack>
			<?php
			$index = 0;
			foreach ( $cards as $card ) :
				$image = severus_image( $card['card_image'] ?? null );
				?>
				<article class="slab" style="--i: <?php echo (int) $index++; ?>">
					<div class="slab__in">
						<?php if ( $image ) : ?>
							<div class="slab__art">
								<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>" loading="lazy">
							</div>
						<?php endif; ?>

						<div class="slab__body">
							<?php if ( $heading = ( $card['card_heading'] ?? '' ) ) : ?>
								<h3><?php echo wp_kses_post( $heading ); ?></h3>
							<?php endif; ?>
							<?php if ( $text = ( $card['card_text'] ?? '' ) ) : ?>
								<p><?php echo wp_kses_post( $text ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
