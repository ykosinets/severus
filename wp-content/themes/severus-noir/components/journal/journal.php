<?php
/**
 * Journal — a few chosen articles.
 *
 * $data (all optional; the front page fields are the defaults):
 *   posts                post ids or objects
 *   label, title         the header
 *   button               ACF link under the grid
 */
defined( 'ABSPATH' ) || exit;

$posts = severus_ids( $data['posts'] ?? get_field( 'products_list' ) );

if ( ! $posts ) {
	return;
}

$label  = $data['label'] ?? '';
$title  = $data['title'] ?? get_field( 'products_title' );
$button = $data['button'] ?? get_field( 'products_button' );
?>
<section class="section is-narrow journal" id="insights">
	<div class="shell">

		<?php if ( $label ) : ?>
			<p class="eyebrow"><?php echo esc_html( $label ); ?></p>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h2 class="journal__title reveal is-display"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>

		<div class="journal__grid">
			<?php
			foreach ( $posts as $id ) :
				?>
				<a class="post reveal" href="<?php echo esc_url( get_permalink( $id ) ); ?>">
					<?php if ( has_post_thumbnail( $id ) ) : ?>
						<span class="post__art">
							<?php echo get_the_post_thumbnail( $id, 'medium_large', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $id ) ) ) ); ?>
						</span>
					<?php endif; ?>

					<h3 class="post__title"><?php echo esc_html( get_the_title( $id ) ); ?></h3>

					<span class="post__meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $id ) ); ?>"><?php echo esc_html( get_the_date( '', $id ) ); ?></time>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php if ( $button ) : ?>
			<div class="journal__foot reveal">
				<?php severus_button( $button, 'btn btn--quiet' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
