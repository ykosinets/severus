<?php
/**
 * Mobile drawer: fills the viewport minus the header, second level expands.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="drawer" id="site-drawer" hidden>
	<div class="drawer__in">

		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'drawer__list',
					'depth'          => 2,
					'fallback_cb'    => false,
				)
			);
		}
		?>

		<p class="drawer__note">
			<?php
			printf(
				/* translators: %s: current year. */
				esc_html__( 'Copyright © %s Severus Inc. All Rights Reserved.', 'severus-noir' ),
				esc_html( wp_date( 'Y' ) )
			);
			?>
		</p>

	</div>
</div>
