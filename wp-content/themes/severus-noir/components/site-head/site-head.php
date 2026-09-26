<?php
/**
 * Site header: logo, primary navigation, contact call to action, drawer trigger.
 */
defined( 'ABSPATH' ) || exit;

$contact = severus_contact_url();
?>
<header class="site-head">
	<div class="shell site-head__in">

		<?php if ( has_custom_logo() ) : ?>
			<div class="site-head__logo"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a class="site-head__logo site-head__logo--text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>
		<?php endif; ?>

		<?php severus_component( 'site-nav' ); ?>

		<button class="theme-toggle" type="button" aria-pressed="false">
			<span class="theme-toggle__sun" aria-hidden="true">☼</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Enable light mode', 'severus-noir' ); ?></span>
		</button>

		<a class="btn btn--solid site-head__cta" href="<?php echo esc_url( $contact ); ?>">
			<svg class="btn__mail" viewBox="0 0 20 16" aria-hidden="true" focusable="false">
				<rect x="1" y="1.5" width="18" height="13" rx="2.6" fill="none" stroke="currentColor" stroke-width="1.5" />
				<path d="M2 4.6l6.9 4.8a2 2 0 0 0 2.2 0L18 4.6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
			<?php esc_html_e( 'Contact Us', 'severus-noir' ); ?>
		</a>

		<button class="burger" type="button" aria-expanded="false" aria-controls="site-drawer" aria-label="<?php esc_attr_e( 'Open menu', 'severus-noir' ); ?>">
			<span></span><span></span>
		</button>

	</div>
</header>
