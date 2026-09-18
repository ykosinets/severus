<?php
/**
 * Before and after: two panels side by side, the second one lit.
 *
 * $data: label, title, items [title, text] (the first is "without", the
 * second "with"), id.
 */
defined( 'ABSPATH' ) || exit;

$items = array_values(
	array_filter( (array) ( $data['items'] ?? array() ), static fn( $item ) => ! empty( $item['title'] ) || ! empty( $item['text'] ) )
);

if ( ! $items ) {
	return;
}
?>
<section class="section is-narrow shift"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell">
		<?php severus_lead( $data ); ?>

		<div class="shift__pair">
			<?php foreach ( $items as $index => $item ) : ?>
				<article class="shift__panel reveal<?php echo $index > 0 ? ' shift__panel--lit edge' : ''; ?>">
					<?php if ( ! empty( $item['title'] ) ) : ?>
						<h3 class="shift__title"><?php echo esc_html( wp_strip_all_tags( $item['title'] ) ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $item['text'] ) ) : ?>
						<p class="shift__text"><?php echo wp_kses_post( $item['text'] ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
