<?php
/**
 * "We Start With the Problem." — the four statements, each ticked as the line
 * crosses the middle of the viewport.
 */
defined( 'ABSPATH' ) || exit;

$title = get_field( 'swp_title' );

if ( ! $title && ! have_rows( 'scrollact_items' ) ) {
	return;
}
?>
<section class="section is-narrow method">
	<div class="shell">

		<div class="method__head">
			<?php if ( $title ) : ?>
				<h2 class="method__title reveal is-display"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>

			<div class="method__notes reveal">
				<?php if ( $subtitle = get_field( 'swp_subtitle' ) ) : ?>
					<p class="method__sub"><?php echo wp_kses_post( $subtitle ); ?></p>
				<?php endif; ?>
				<?php if ( $description = get_field( 'swp_description' ) ) : ?>
					<p class="method__desc"><?php echo wp_kses_post( $description ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( have_rows( 'scrollact_items' ) ) : ?>
			<ol class="steps">
				<?php
				while ( have_rows( 'scrollact_items' ) ) :
					the_row();
					$text = get_sub_field( 'swp_item_text' );
					if ( ! $text ) {
						continue;
					}
					?>
					<li class="steps__item rule">
						<span class="steps__tick" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path pathLength="1" d="M4 12.5 9.5 18 20 6.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<p class="is-quiet"><?php echo wp_kses_post( $text ); ?></p>
					</li>
				<?php endwhile; ?>
			</ol>
		<?php endif; ?>

	</div>
</section>
