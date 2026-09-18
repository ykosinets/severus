<?php
/**
 * A grid of linked picture tiles — services on About, industries on the
 * industries page.
 *
 * $data: label, title, text, items [title, text, url, image (severus_image)], id.
 */
defined( 'ABSPATH' ) || exit;

$items = array_values(
	array_filter( (array) ( $data['items'] ?? array() ), static fn( $item ) => ! empty( $item['title'] ) )
);

if ( ! $items ) {
	return;
}
?>
<section class="section is-narrow tiles"<?php echo ! empty( $data['id'] ) ? ' id="' . esc_attr( $data['id'] ) . '"' : ''; ?>>
	<div class="shell">
		<?php severus_lead( $data, array( 'split' => ! empty( $data['text'] ) ) ); ?>

		<div class="tiles__grid">
			<?php foreach ( $items as $item ) : ?>
				<?php $tag = empty( $item['url'] ) ? 'div' : 'a'; ?>
				<<?php echo $tag; ?> class="tile reveal"<?php echo 'a' === $tag ? ' href="' . esc_url( $item['url'] ) . '" data-snake-arrow' : ''; ?>>
					<?php if ( ! empty( $item['image'] ) ) : ?>
						<span class="tile__art">
							<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="" width="<?php echo esc_attr( $item['image']['width'] ); ?>" height="<?php echo esc_attr( $item['image']['height'] ); ?>" loading="lazy">
						</span>
					<?php endif; ?>
					<span class="tile__body">
						<span class="tile__title"><?php echo esc_html( wp_strip_all_tags( $item['title'] ) ); ?></span>
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<span class="tile__text"><?php echo wp_kses_post( $item['text'] ); ?></span>
						<?php endif; ?>
						<?php if ( 'a' === $tag ) : ?>
							<span class="tile__go"><?php severus_arrow(); ?></span>
						<?php endif; ?>
					</span>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
