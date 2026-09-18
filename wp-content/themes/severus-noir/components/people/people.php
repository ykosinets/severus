<?php
/**
 * Our experts. Portraits with a quote that surfaces on hover.
 */
defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'experts_list' ) ) {
	return;
}
?>
<section class="section section--floor people" id="experts">
	<div class="shell">

		<header class="lead lead--split reveal">
			<?php if ( $title = get_field( 'experts_title' ) ) : ?>
				<h2 class="lead__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro = get_field( 'experts_intro' ) ) : ?>
				<p class="lead__text"><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="people__grid">
			<?php
			while ( have_rows( 'experts_list' ) ) :
				the_row();
				$photo    = severus_image( get_sub_field( 'expert_photo' ) );
				$name     = get_sub_field( 'expert_name' );
				$linkedin = get_sub_field( 'expert_linkedin' );
				?>
				<article class="face reveal">
					<div class="face__frame">
						<?php if ( $photo ) : ?>
							<img src="<?php echo esc_url( $photo['url'] ); ?>" alt="<?php echo esc_attr( $photo['alt'] ?: $name ); ?>" width="<?php echo esc_attr( $photo['width'] ); ?>" height="<?php echo esc_attr( $photo['height'] ); ?>" loading="lazy">
						<?php endif; ?>

						<div class="face__veil">
							<?php if ( $quote = get_sub_field( 'expert_quote' ) ) : ?>
								<blockquote><?php echo wp_kses_post( $quote ); ?></blockquote>
							<?php endif; ?>
							<?php if ( $overlay = get_sub_field( 'expert_overlay_role' ) ) : ?>
								<p><?php echo esc_html( $overlay ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<h3 class="face__name">
						<?php echo esc_html( $name ); ?>
						<?php if ( $linkedin ) : ?>
							<a class="face__ln" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: person's name. */ __( '%s on LinkedIn', 'severus-noir' ), $name ) ); ?>">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
									<path fill="currentColor" d="M4.98 3.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5ZM3 9h4v12H3V9Zm7 0h3.8v1.7h.05c.53-.95 1.83-1.95 3.76-1.95C21.4 8.75 22 11 22 14.1V21h-4v-6.1c0-1.45-.03-3.3-2.02-3.3-2.02 0-2.33 1.57-2.33 3.2V21h-4V9Z" />
								</svg>
							</a>
						<?php endif; ?>
					</h3>

					<?php if ( $role = get_sub_field( 'expert_role' ) ) : ?>
						<p class="face__role"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
		</div>

	</div>
</section>
