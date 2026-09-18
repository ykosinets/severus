<?php
/**
 * "Sound Familiar?" — a stack of slabs that pin and shrink under the next one.
 */
defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'sound_familiar_cards' ) ) {
	return;
}
?>
<section class="section section--cool is-narrow familiar" id="sound-familiar">
	<div class="shell">

		<?php /* Pinned while the slabs scroll, so no .reveal — a view() timeline
			stalls on a sticky element. */ ?>
		<header class="lead familiar__lead" data-stack-lead>
			<?php if ( $title = get_field( 'sound_familiar_title' ) ) : ?>
				<h2 class="lead__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro = get_field( 'sound_familiar_intro' ) ) : ?>
				<p class="lead__text"><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="stack" data-stack>
			<?php
			$index = 0;
			while ( have_rows( 'sound_familiar_cards' ) ) :
				the_row();
				$image = severus_image( get_sub_field( 'card_image' ) );
				?>
				<article class="slab" style="--i: <?php echo (int) $index++; ?>">
					<div class="slab__in">
						<?php if ( $image ) : ?>
							<div class="slab__art">
								<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>" loading="lazy">
							</div>
						<?php endif; ?>

						<div class="slab__body">
							<?php if ( $heading = get_sub_field( 'card_heading' ) ) : ?>
								<h3><?php echo wp_kses_post( $heading ); ?></h3>
							<?php endif; ?>
							<?php if ( $text = get_sub_field( 'card_text' ) ) : ?>
								<p><?php echo wp_kses_post( $text ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

	</div>
</section>
