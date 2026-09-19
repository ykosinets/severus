<?php
/**
 * Site footer: brand, Reach Out, Offices, Explore, Our Services — in the
 * previous theme's order, with its titles and offices from the theme
 * settings.
 */
defined( 'ABSPATH' ) || exit;

/* Column titles and offices come from the theme settings. */
$option  = static fn( string $name ) => severus_field( $name, 'option' );
$offices = array_filter(
	severus_rows( 'footer_offices', 'option' ),
	static fn( $office ) => ! empty( $office['office_country'] ) || ! empty( $office['office_address'] )
);
?>
<footer class="site-foot">
	<div class="shell">

		<div class="site-foot__top<?php echo $offices ? ' site-foot__top--offices' : ''; ?>">
			<div class="site-foot__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php endif; ?>
				<?php
				/* Theme settings → footer_tagline, as the previous theme used; the
				   site tagline only stands in when that is empty. */
				$tagline = $option( 'footer_tagline' );
				?>
				<?php if ( $tagline ) : ?>
					<p class="site-foot__claim"><?php echo wp_kses_post( nl2br( $tagline ) ); ?></p>
				<?php else : ?>
					<p class="site-foot__claim"><?php bloginfo( 'name' ); ?> — <?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
			</div>

			<div class="site-foot__col">
				<h3><?php echo esc_html( $option( 'footer_reach_title' ) ?: __( 'Get in touch', 'severus-noir' ) ); ?></h3>
				<?php if ( $email = severus_contact_email() ) : ?>
					<?php if ( $label = $option( 'footer_email_label' ) ) : ?>
						<p class="site-foot__label"><?php echo esc_html( $label ); ?></p>
					<?php endif; ?>
					<a class="site-foot__mail" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<?php endif; ?>
				<?php if ( $linkedin = get_theme_mod( 'severus_linkedin' ) ) : ?>
					<a class="site-foot__ln" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener">LinkedIn</a>
				<?php endif; ?>
			</div>

			<?php if ( $offices ) : ?>
				<div class="site-foot__col">
					<h3><?php echo esc_html( $option( 'footer_offices_title' ) ?: __( 'Offices', 'severus-noir' ) ); ?></h3>
					<ul class="offices">
						<?php
						foreach ( $offices as $office ) :
							$flag = severus_image( $office['office_flag'] ?? null );
							?>
							<li class="office">
								<?php if ( ! empty( $office['office_country'] ) ) : ?>
									<p class="office__country">
										<?php if ( $flag ) : ?>
											<img src="<?php echo esc_url( $flag['url'] ); ?>" alt="" width="21" height="15" loading="lazy">
										<?php endif; ?>
										<?php echo esc_html( $office['office_country'] ); ?>
									</p>
								<?php endif; ?>
								<?php if ( ! empty( $office['office_address'] ) ) : ?>
									<address class="office__address"><?php echo wp_kses_post( nl2br( trim( $office['office_address'] ) ) ); ?></address>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php
			$menus = array(
				'footer_pages'    => 'footer_explore_title',
				'footer_services' => 'footer_services_title',
			);

			foreach ( $menus as $location => $title_field ) :
				if ( ! has_nav_menu( $location ) ) {
					continue;
				}
				?>
				<nav class="site-foot__col<?php echo 'footer_services' === $location ? ' site-foot__col--wide' : ''; ?>">
					<h3><?php echo esc_html( $option( $title_field ) ?: severus_menu_title( $location ) ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $location,
							'container'      => false,
							'menu_class'     => 'site-foot__list',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endforeach; ?>
		</div>

		<div class="site-foot__bar rule">
			<span>
				<?php
				printf(
					/* translators: 1: year, 2: site name. */
					esc_html__( 'Copyright © %1$s %2$s. All Rights Reserved.', 'severus-noir' ),
					esc_html( wp_date( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
		</div>

	</div>
</footer>
