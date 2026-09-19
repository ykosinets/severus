<?php
/**
 * The side panel of the booking dialog, as a template calendly.js clones.
 * Calendly's own event details are hidden in the embed (they carried its
 * privacy link and a photo the dark filter would invert), so they are shown
 * here from the Theme settings → Booking dialog fields instead.
 */
defined( 'ABSPATH' ) || exit;

$option   = static fn( string $name, string $fallback = '' ) => severus_field( $name, 'option' ) ?: $fallback;
$photo    = (int) $option( 'booking_photo' );
$host     = $option( 'booking_host', 'Olena Bochulia' );
$title    = $option( 'booking_title', 'Severus | Helping business owners to scale and grow' );
$duration = $option( 'booking_duration', '30 min' );
$note     = $option( 'booking_note', 'Web conferencing details provided upon confirmation.' );

$privacy = get_privacy_policy_url();
if ( ! $privacy ) {
	$page    = get_page_by_path( 'privacy-policy' );
	$privacy = $page ? get_permalink( $page ) : '';
}
?>
<template id="severus-booking">
	<aside class="booking__aside">
		<?php if ( $photo ) : ?>
			<?php echo wp_get_attachment_image( $photo, 'thumbnail', false, array( 'class' => 'booking__photo', 'alt' => '', 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
		<?php if ( $host ) : ?>
			<p class="booking__host"><?php echo esc_html( $host ); ?></p>
		<?php endif; ?>
		<h2 class="booking__title"><?php echo esc_html( $title ); ?></h2>
		<ul class="booking__facts">
			<?php if ( $duration ) : ?>
				<li>
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
					<?php echo esc_html( $duration ); ?>
				</li>
			<?php endif; ?>
			<?php if ( $note ) : ?>
				<li>
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="6" width="13" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M16 10l5-3v10l-5-3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
					<?php echo esc_html( $note ); ?>
				</li>
			<?php endif; ?>
		</ul>
		<p class="booking__legal">
			<?php esc_html_e( 'Scheduling is provided by Calendly.', 'severus-noir' ); ?>
			<?php if ( $privacy ) : ?>
				<a href="<?php echo esc_url( $privacy ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Privacy Policy', 'severus-noir' ); ?></a>
			<?php endif; ?>
		</p>
	</aside>
</template>
