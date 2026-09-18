<?php
/**
 * A closing call to action on a lit panel, with three faceted shapes turning
 * behind the copy (callout.js mounts them into one WebGL scene).
 *
 * $data: title, text, button (ACF link), id.
 */
defined( 'ABSPATH' ) || exit;

$title = $data['title'] ?? '';

if ( ! $title && empty( $data['button'] ) ) {
	return;
}
?>
<section class="section is-narrow callout"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell">
		<div class="callout__panel edge reveal">
			<span class="callout__glow" aria-hidden="true"></span>
			<span class="callout__shapes" aria-hidden="true" data-shapes></span>
			<?php if ( $title ) : ?>
				<h2 class="callout__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $data['text'] ) ) : ?>
				<p class="callout__text"><?php echo wp_kses_post( $data['text'] ); ?></p>
			<?php endif; ?>
			<?php severus_button( $data['button'] ?? null, 'btn btn--solid' ); ?>
		</div>
	</div>
</section>
