<?php
/**
 * Numbered points under a section header, either as a row of cards or as a
 * ruled list.
 *
 * $data: label, title, text, items [title, text], layout 'cards'|'list', id.
 */
defined( 'ABSPATH' ) || exit;

$items = array_values(
	array_filter( (array) ( $data['items'] ?? array() ), static fn( $item ) => ! empty( $item['title'] ) || ! empty( $item['text'] ) )
);

if ( ! $items ) {
	return;
}

$layout = 'list' === ( $data['layout'] ?? '' ) ? 'list' : 'cards';
?>
<section class="section is-narrow points points--<?php echo esc_attr( $layout ); ?>"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell">
		<?php severus_lead( $data, array( 'split' => 'list' === $layout ) ); ?>

		<ol class="points__list">
			<?php foreach ( $items as $index => $item ) : ?>
				<li class="point reveal<?php echo 'cards' === $layout ? ' edge' : ' rule'; ?>">
					<span class="point__n"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<div class="point__body">
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3 class="point__title"><?php echo esc_html( wp_strip_all_tags( $item['title'] ) ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<p class="point__text"><?php echo wp_kses_post( $item['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
