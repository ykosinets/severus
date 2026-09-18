<?php
/**
 * Not found.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="section">
	<div class="shell">
		<header class="lead">
			<h1 class="lead__title"><?php esc_html_e( 'That page has moved on.', 'severus-noir' ); ?></h1>
			<p class="lead__text"><?php esc_html_e( 'The address is wrong, or the page is gone. Start again from the home page.', 'severus-noir' ); ?></p>
		</header>

		<a class="btn btn--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>" data-snake-arrow>
			<?php esc_html_e( 'Back to the home page', 'severus-noir' ); ?>
			<?php severus_arrow(); ?>
		</a>
	</div>
</section>

<?php
get_footer();
