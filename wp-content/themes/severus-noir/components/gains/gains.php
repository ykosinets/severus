<?php
/**
 * What the client gets: a two-column list, each line ticked.
 *
 * $data: label, title, text, items [title, text], id.
 */
defined( 'ABSPATH' ) || exit;

$items = array_values(
	array_filter( (array) ( $data['items'] ?? array() ), static fn( $item ) => ! empty( $item['title'] ) || ! empty( $item['text'] ) )
);

if ( ! $items ) {
	return;
}
?>
<section class="section section--warm is-narrow gains"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell">
		<?php severus_lead( $data, array( 'split' => true ) ); ?>

		<ul class="gains__list">
			<?php foreach ( $items as $item ) : ?>
				<li class="gain rule reveal">
					<span class="gain__tick" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false"><path d="M4 12.5 9.5 18 20 6.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" /></svg>
					</span>
					<div>
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3 class="gain__title"><?php echo esc_html( wp_strip_all_tags( $item['title'] ) ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<p class="gain__text"><?php echo wp_kses_post( $item['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
