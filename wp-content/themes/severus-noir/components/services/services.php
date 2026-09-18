<?php
/**
 * Services: cards three to a row. Each shows the service's glyph, its excerpt
 * (service_short_descr), the ticked card list, a link, and the card
 * background behind it — all from the service's card fields.
 *
 * $data (all optional; the front page fields are the defaults):
 *   services            post ids or objects; on the front page, every
 *                       top-level service (in services_list order), or the
 *                       first three of services_list if there are none
 *   limit               how many to show
 *   label, title, text  the header
 *   button              ACF link under the cards
 *   id                  section id
 *   feature             true for the Services page: a row per top-level
 *                       service (lit border), its children listed beside it
 *                       as horizontal rows
 */
defined( 'ABSPATH' ) || exit;

if ( isset( $data['services'] ) ) {
	$services = severus_ids( $data['services'] );
	$limit    = $data['limit'] ?? 0;
} else {
	$listed = severus_ids( get_field( 'services_list' ) );
	$top    = get_posts(
		array(
			'post_type'   => 'service',
			'numberposts' => -1,
			'fields'      => 'ids',
			'post_parent' => 0,
			'orderby'     => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);

	// Listed ones keep the list's order; any others follow.
	$services = $top
		? array_merge( array_values( array_intersect( $listed, $top ) ), array_values( array_diff( $top, $listed ) ) )
		: $listed;
	$limit    = $data['limit'] ?? ( $top ? 0 : 3 );
}

if ( $limit ) {
	$services = array_slice( $services, 0, $limit );
}

if ( ! $services ) {
	return;
}

$feature = ! empty( $data['feature'] );

/* The Services page: each top-level service starts a row, its children follow
   it on the same row; anything whose parent is not on show comes after. */
$groups = array();
$loose  = $services;

if ( $feature ) {
	$parents = array_values( array_filter( $services, static fn( $id ) => ! wp_get_post_parent_id( $id ) ) );

	foreach ( $parents as $parent ) {
		$groups[ $parent ] = array_values( array_filter( $services, static fn( $id ) => (int) wp_get_post_parent_id( $id ) === $parent ) );
	}

	$placed = array_merge( $parents, ...array_values( $groups ) );
	$loose  = array_values( array_diff( $services, $placed ) );
}

/**
 * One service card.
 */
$card = static function ( int $id, string $modifier = '' ): void {
	$glyph   = severus_inline_svg( get_field( 'service_icon', $id ) );
	$art     = severus_image( get_field( 'service_card_image', $id ) );
	$points  = array_filter( wp_list_pluck( (array) get_field( 'service_card_points', $id ), 'text' ) );
	$summary = get_field( 'service_short_descr', $id );
	?>
	<a class="<?php echo esc_attr( trim( 'card ' . $modifier ) ); ?>" href="<?php echo esc_url( get_permalink( $id ) ); ?>" data-snake-arrow>
		<?php if ( $art ) : ?>
			<span class="card__art" aria-hidden="true">
				<img src="<?php echo esc_url( $art['url'] ); ?>" alt="" width="<?php echo esc_attr( $art['width'] ); ?>" height="<?php echo esc_attr( $art['height'] ); ?>" loading="lazy">
			</span>
		<?php endif; ?>

		<?php if ( $glyph ) : ?>
			<span class="card__glyph"><?php echo $glyph; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — sanitised in severus_inline_svg(). ?></span>
		<?php endif; ?>

		<h3 class="card__title"><?php echo esc_html( get_the_title( $id ) ); ?></h3>

		<?php if ( $summary ) : ?>
			<p class="card__text"><?php echo wp_kses_post( $summary ); ?></p>
		<?php endif; ?>

		<?php if ( $points ) : ?>
			<ul class="card__points">
				<?php foreach ( $points as $point ) : ?>
					<li><?php echo esc_html( wp_strip_all_tags( $point ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<span class="card__more">
			<?php esc_html_e( 'Learn more', 'severus-noir' ); ?>
			<span class="card__go" aria-hidden="true"><?php severus_arrow(); ?></span>
		</span>
	</a>
	<?php
};

/**
 * A child service as a plain row beside its parent: glyph, title and
 * summary, and a button.
 */
$row = static function ( int $id ): void {
	$glyph   = severus_inline_svg( get_field( 'service_icon', $id ) );
	$summary = get_field( 'service_short_descr', $id );
	$url     = get_permalink( $id );
	?>
	<div class="sub">
		<?php if ( $glyph ) : ?>
			<span class="sub__glyph card__glyph" aria-hidden="true"><?php echo $glyph; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — sanitised in severus_inline_svg(). ?></span>
		<?php endif; ?>
		<div class="sub__body">
			<h4 class="sub__title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $id ) ); ?></a></h4>
			<?php if ( $summary ) : ?>
				<p class="sub__text"><?php echo wp_kses_post( $summary ); ?></p>
			<?php endif; ?>
		</div>
		<?php severus_button( array( 'url' => $url, 'title' => __( 'Learn more', 'severus-noir' ) ), 'btn btn--quiet sub__btn' ); ?>
	</div>
	<?php
};

/* Where a group has a single child, the spare room offers a call instead. */
$front   = (int) get_option( 'page_on_front' );
$booking = $front ? get_field( 'hero_btn_primary', $front ) : null;

$lead = array(
	'label' => $data['label'] ?? '',
	'title' => $data['title'] ?? get_field( 'services_title' ),
	'text'  => $data['text'] ?? get_field( 'services_subtitle' ),
);
?>
<section class="section section--warm services" id="<?php echo esc_attr( $data['id'] ?? 'services' ); ?>">
	<div class="shell">

		<?php severus_lead( $lead, array( 'split' => true ) ); ?>

		<?php if ( $groups ) : ?>
			<div class="families">
				<?php foreach ( $groups as $parent => $children ) : ?>
					<div class="family">
						<?php $card( $parent, 'card--important' ); ?>
						<?php if ( $children ) : ?>
							<div class="family__rows">
								<?php
								foreach ( $children as $child ) {
									$row( $child );
								}
								?>
								<?php if ( 1 === count( $children ) && ! empty( $booking['url'] ) ) : ?>
									<div class="sub sub--ask">
										<div class="sub__body">
											<h4 class="sub__title"><?php esc_html_e( 'Not sure which one you need?', 'severus-noir' ); ?></h4>
											<p class="sub__text"><?php esc_html_e( 'That’s the right place to start. Book a free call and we’ll point you to where it pays off first.', 'severus-noir' ); ?></p>
										</div>
										<?php severus_button( $booking, 'btn btn--solid sub__btn' ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $loose ) : ?>
			<div class="cards<?php echo $groups ? ' cards--rest' : ''; ?>">
				<?php
				foreach ( $loose as $id ) {
					$card( $id );
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( $button = ( $data['button'] ?? get_field( 'services_button' ) ) ) : ?>
			<div class="services__foot reveal">
				<?php severus_button( $button, 'btn btn--solid' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
