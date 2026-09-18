<?php
/**
 * A copy-led section: eyebrow, title, a paragraph and an optional aside note.
 *
 * $data: label, title, text, note, id.
 */
defined( 'ABSPATH' ) || exit;

$title = $data['title'] ?? '';
$text  = $data['text'] ?? '';
$note  = $data['note'] ?? '';

if ( ! $title && ! $text && ! $note ) {
	return;
}
?>
<section class="section is-narrow brief"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell brief__in">
		<div class="brief__head reveal">
			<?php if ( ! empty( $data['label'] ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $data['label'] ); ?></p>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h2 class="brief__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
		</div>

		<div class="brief__body reveal">
			<?php if ( $text ) : ?>
				<div class="brief__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
			<?php endif; ?>
			<?php if ( $note ) : ?>
				<div class="brief__note rule"><?php echo wp_kses_post( wpautop( $note ) ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
