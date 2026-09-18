<?php
/**
 * Desktop primary navigation. Core menu output — the second level is styled
 * into a panel rather than built with a custom walker.
 */
defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'primary' ) ) {
	return;
}
?>
<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'severus-noir' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav__list',
			'depth'          => 2,
			'fallback_cb'    => false,
		)
	);
	?>
</nav>
