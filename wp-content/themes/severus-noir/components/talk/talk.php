<?php
/**
 * Contact: the pitch and the address beside the theme's own form.
 *
 * $data (all optional; the front page fields are the defaults):
 *   label, title, subtitle, text   the side column
 *   heading                        'h1' when this is the page's main block
 *   location                       a postal address line
 *   id                             section id
 *   orbit                          'left' | 'right' — the orbit mark over the top edge
 */
defined( 'ABSPATH' ) || exit;

$title = $data['title'] ?? get_field( 'contact_title' );

if ( ! $title ) {
	return;
}

$heading  = 'h1' === ( $data['heading'] ?? '' ) ? 'h1' : 'h2';
$label    = $data['label'] ?? '';
$subtitle = $data['subtitle'] ?? '';
$text     = $data['text'] ?? get_field( 'contact_text' );
$location = $data['location'] ?? '';
$logo     = isset( $data['title'] ) ? null : severus_image( get_field( 'contact_logo' ) );
?>
<section class="section talk" id="<?php echo esc_attr( $data['id'] ?? 'home-contacts' ); ?>">
	<?php if ( ! empty( $data['orbit'] ) ) : ?>
		<?php severus_orbit( 'orbit--deco orbit--' . $data['orbit'], 'page' ); ?>
	<?php endif; ?>
	<div class="shell talk__in">

		<div class="talk__side">
			<?php if ( $logo ) : ?>
				<img class="talk__logo" src="<?php echo esc_url( $logo['url'] ); ?>" alt="" width="<?php echo esc_attr( $logo['width'] ); ?>" height="<?php echo esc_attr( $logo['height'] ); ?>" loading="lazy">
			<?php endif; ?>

			<?php if ( $label ) : ?>
				<p class="eyebrow"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>

			<<?php echo $heading; ?> class="talk__title reveal is-display"><?php echo wp_kses_post( $title ); ?></<?php echo $heading; ?>>

			<?php if ( $subtitle ) : ?>
				<p class="talk__sub reveal"><?php echo wp_kses_post( $subtitle ); ?></p>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<p class="talk__text reveal"><?php echo wp_kses_post( $text ); ?></p>
			<?php endif; ?>

			<dl class="talk__facts">
				<?php if ( $location ) : ?>
					<div>
						<dt><?php esc_html_e( 'Location', 'severus-noir' ); ?></dt>
						<dd><?php echo esc_html( $location ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $email = severus_contact_email() ) : ?>
					<div>
						<?php if ( $location ) : ?>
							<dt><?php esc_html_e( 'Email', 'severus-noir' ); ?></dt>
						<?php endif; ?>
						<dd><a class="talk__mail" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></dd>
					</div>
				<?php endif; ?>
			</dl>
		</div>

		<?php severus_component( 'contact-form' ); ?>

	</div>
</section>
