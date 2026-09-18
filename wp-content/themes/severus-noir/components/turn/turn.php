<?php
/**
 * The transition line. A short band; the Severus mark opens up as a mask over a
 * looping backdrop while the band crosses the viewport.
 */
defined( 'ABSPATH' ) || exit;

$title = get_field( 'section_info_title' );

if ( ! $title ) {
	return;
}

$loop = get_theme_file_uri( 'assets/media/turn-loop.mp4' );
?>
<section class="turn" data-turn>
	<div class="turn__pin">

		<div class="turn__reveal">
			<div class="turn__glow" aria-hidden="true">
				<video class="turn__video" src="<?php echo esc_url( $loop ); ?>" muted loop playsinline autoplay preload="auto"></video>
			</div>
			<div class="shell">
				<p class="turn__text is-quiet"><?php echo wp_kses_post( $title ); ?></p>
			</div>
		</div>

		<a class="turn__cue" href="#services" aria-label="<?php esc_attr_e( 'Continue', 'severus-noir' ); ?>" data-snake-arrow>
			<?php severus_arrow(); ?>
		</a>

	</div>
</section>
