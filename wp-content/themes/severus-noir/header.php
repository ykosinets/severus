<?php
/**
 * Document head, and everything above the main content.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="formcanary-verification" content="fc_verify_Q8cfEnLa9taYdNRt-fkSlEmPVCFoiTTV">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="grain" aria-hidden="true"></div>
<div class="readbar" aria-hidden="true"><i></i></div>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'severus-noir' ); ?></a>

<?php
severus_component( 'site-head' );
severus_component( 'drawer' );
?>

<main id="main" class="site-main">
	<?php if ( ! is_front_page() ) : ?>
		<div class="site-main__aura" aria-hidden="true"></div>
	<?php endif; ?>
