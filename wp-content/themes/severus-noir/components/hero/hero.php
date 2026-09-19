<?php
/**
 * Hero: headline, lede, calls to action, the video card and the stats strip.
 *
 * The layout follows the previous theme's hero, which the client asked to keep:
 * a framed video card on the right with a play button and rings centred on its
 * left edge, and a bordered strip of three stats underneath.
 */
defined( 'ABSPATH' ) || exit;

$title = severus_field( 'hero_title' );
$intro = severus_field( 'hero_intro' );

if ( ! $title && ! $intro ) {
	return;
}

$video  = severus_file_url( severus_field( 'hero_video' ) );
$poster = severus_image( severus_field( 'hero_poster' ) );
$still  = severus_lightbox_still( $poster );
?>
<section class="hero">
	<div class="shell hero__in">

		<?php if ( $title ) : ?>
			<h1 class="hero__title reveal"><?php echo wp_kses_post( $title ); ?></h1>
		<?php endif; ?>

		<div class="hero__grid">

			<div class="hero__lede reveal">
				<?php if ( $intro ) : ?>
					<p><?php echo wp_kses_post( $intro ); ?></p>
				<?php endif; ?>

				<div class="hero__actions">
					<?php
					severus_button( severus_field( 'hero_btn_primary' ), 'btn btn--solid' );
					severus_button( severus_field( 'hero_btn_secondary' ), 'btn btn--quiet' );
					?>
				</div>
			</div>

			<?php if ( $video && $poster ) : ?>
				<div class="hero__media reveal">
					<div class="stage">
						<span class="stage__glow" aria-hidden="true"></span>
						<span class="stage__ring stage__ring--lg" aria-hidden="true"></span>
						<span class="stage__ring stage__ring--md" aria-hidden="true"></span>

						<button
							class="stage__card"
							type="button"
							data-video-open
							data-video="<?php echo esc_url( $video ); ?>"
							data-poster="<?php echo esc_url( $poster['url'] ); ?>"
							data-still="<?php echo esc_url( $still['url'] ); ?>"
							data-width="<?php echo esc_attr( $still['width'] ); ?>"
							data-height="<?php echo esc_attr( $still['height'] ); ?>"
							aria-label="<?php esc_attr_e( 'Watch a video', 'severus-noir' ); ?>"
						>
							<span class="stage__frame">
								<img class="stage__poster" src="<?php echo esc_url( $poster['url'] ); ?>" alt="" width="<?php echo esc_attr( $poster['width'] ); ?>" height="<?php echo esc_attr( $poster['height'] ); ?>" fetchpriority="high">

								<span class="stage__tag stage__tag--view">
									<i aria-hidden="true"></i><?php esc_html_e( 'View', 'severus-noir' ); ?>
								</span>

								<?php if ( $duration = severus_field( 'hero_video_duration' ) ) : ?>
									<span class="stage__tag stage__tag--time"><?php echo esc_html( $duration ); ?></span>
								<?php endif; ?>
							</span>

							<span class="stage__play" aria-hidden="true">
								<span class="stage__ring stage__ring--sm"></span>
								<svg viewBox="0 0 44 44" focusable="false">
									<path d="M36.59 16.77c3.8 2.07 3.8 7.42 0 9.49L13.62 38.75c-3.7 2.01-8.24-.6-8.24-4.75V9.02c0-4.14 4.54-6.75 8.24-4.75l22.97 12.5z" fill="none" stroke="currentColor" stroke-width="3.26" />
								</svg>
							</span>
						</button>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( $stats = severus_rows( 'hero_stats' ) ) : ?>
			<ul class="stats">
				<?php
				foreach ( $stats as $stat ) :
					$icon = severus_image( $stat['stats_icon'] ?? null );
					$link = $stat['stats_link'] ?? '';
					$link = is_array( $link ) ? ( $link['url'] ?? '' ) : $link;

					/* The link holds only an icon, so it is named after the site it
					   opens: "Severus on Clutch", from clutch.co. */
					$site  = $link ? ucfirst( strtok( preg_replace( '/^www\./', '', (string) wp_parse_url( $link, PHP_URL_HOST ) ), '.' ) ) : '';
					$label = $site
						/* translators: %s: the site the link opens, e.g. Clutch. */
						? sprintf( __( 'Severus on %s (opens in a new tab)', 'severus-noir' ), $site )
						: __( 'Opens in a new tab', 'severus-noir' );
					?>
					<li class="stats__item">
						<p>
							<?php echo wp_kses_post( $stat['stats_text'] ?? '' ); ?>
							<?php if ( $icon && $link ) : ?>
								<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $label ); ?>">
									<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="" width="<?php echo esc_attr( $icon['width'] ); ?>" height="<?php echo esc_attr( $icon['height'] ); ?>" loading="lazy">
								</a>
							<?php elseif ( $icon ) : ?>
								<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="" width="<?php echo esc_attr( $icon['width'] ); ?>" height="<?php echo esc_attr( $icon['height'] ); ?>" loading="lazy">
							<?php endif; ?>
						</p>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

	</div>
</section>
