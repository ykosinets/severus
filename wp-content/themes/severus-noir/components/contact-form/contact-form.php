<?php
/**
 * Contact form. Plain markup, submitted with fetch() to the theme's own REST
 * route — no form plugin, and nothing on the page that needs jQuery.
 */
defined( 'ABSPATH' ) || exit;
?>
<form
	class="form"
	data-contact-form
	action="<?php echo esc_url( rest_url( 'severus/v1/contact' ) ); ?>"
	method="post"
	novalidate
>
	<p class="form__note" data-form-note hidden aria-live="polite"></p>

	<div class="field">
		<input id="severus-name" name="name" type="text" placeholder=" " autocomplete="name" maxlength="120" required>
		<label for="severus-name"><?php esc_html_e( 'Full name', 'severus-noir' ); ?> <span aria-hidden="true">*</span></label>
	</div>

	<div class="field">
		<input id="severus-company" name="company" type="text" placeholder=" " autocomplete="organization" maxlength="120" required>
		<label for="severus-company"><?php esc_html_e( 'Company', 'severus-noir' ); ?> <span aria-hidden="true">*</span></label>
	</div>

	<div class="field">
		<input id="severus-email" name="email" type="email" placeholder=" " autocomplete="email" maxlength="180" required>
		<label for="severus-email"><?php esc_html_e( 'Email', 'severus-noir' ); ?> <span aria-hidden="true">*</span></label>
	</div>

	<div class="field field--area">
		<textarea id="severus-details" name="details" placeholder=" " maxlength="180" rows="3" required data-counter></textarea>
		<label for="severus-details"><?php esc_html_e( 'Request details', 'severus-noir' ); ?> <span aria-hidden="true">*</span></label>
		<span class="field__count"><b data-counter-out>0</b> / 180</span>
	</div>

	<?php /* Bots fill this; people never see it. */ ?>
	<div class="field field--trap" aria-hidden="true">
		<label for="severus-website"><?php esc_html_e( 'Leave this empty', 'severus-noir' ); ?></label>
		<input id="severus-website" name="website" type="text" tabindex="-1" autocomplete="off">
	</div>

	<input type="hidden" name="opened" value="<?php echo esc_attr( time() ); ?>">
	<?php wp_nonce_field( 'severus_contact', 'severus_nonce', false ); ?>

	<button class="btn btn--solid btn--block" type="submit" data-snake-arrow>
		<span data-submit-label><?php esc_html_e( 'Submit', 'severus-noir' ); ?></span>
		<?php severus_arrow(); ?>
	</button>
</form>
