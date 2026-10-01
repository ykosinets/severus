<?php
/** Contact form: Forminator owns validation, storage and notifications. */
defined( 'ABSPATH' ) || exit;
?>
<div class="form form--forminator">
	<?php if ( shortcode_exists( 'forminator_form' ) ) : ?>
		<?php echo do_shortcode( '[forminator_form id="82"]' ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Please contact us by email:', 'severus-noir' ); ?>
			<a href="mailto:<?php echo esc_attr( severus_contact_email() ); ?>"><?php echo esc_html( severus_contact_email() ); ?></a>
		</p>
	<?php endif; ?>
</div>
