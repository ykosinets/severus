<?php
/**
 * Where We Create Impact. Pins while scroll steps the crystal
 * slider and cross-fades the copy. The 3D orbit sits behind, on the right.
 */
defined( 'ABSPATH' ) || exit;

$items = isset( $data['slides'] ) ? (array) $data['slides'] : severus_rows( 'impact_items' );

if ( ! $items ) {
	return;
}

$slides = array();

foreach ( $items as $item ) {
	$image  = severus_image( $item['image'] ?? $item['impact_image'] ?? null );
	$button = $item['button'] ?? $item['impact_button'] ?? null;

	$slides[] = array(
		'title' => $item['title'] ?? $item['impact_title'] ?? '',
		'text'  => $item['text'] ?? $item['impact_text'] ?? '',
		'image' => $image ? $image['url'] : '',
		'link'  => is_array( $button ) ? ( $button['url'] ?? '' ) : '',
		'label' => is_array( $button ) ? ( $button['title'] ?? '' ) : '',
	);
}

$images = array_values( array_filter( wp_list_pluck( $slides, 'image' ) ) );
$first  = $slides[0] ?? array();
?>
<section class="impact is-narrow" id="impact" data-impact>
	<div class="impact__pin">

		<?php severus_orbit( 'impact__orbit' ); ?>

		<div class="shell impact__in">

			<div class="impact__copy">
				<header class="lead">
					<?php if ( $title = ( $data['title'] ?? severus_field( 'impact_title' ) ) ) : ?>
						<h2 class="lead__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle = ( $data['subtitle'] ?? severus_field( 'impact_subtitle' ) ) ) : ?>
						<p class="lead__text"><?php echo wp_kses_post( $subtitle ); ?></p>
					<?php endif; ?>
				</header>

				<?php /* An accordion driven by scroll: the open item follows the slider,
					and a title only scrolls to its place (impact.js). */ ?>
				<div class="verticals">
					<?php
					foreach ( $slides as $index => $slide ) :
						$active = 0 === $index;
						$body   = 'impact-vert-' . $index;
						?>
						<article class="vert rule<?php echo $active ? ' is-active' : ''; ?>" data-vert="<?php echo esc_attr( $index ); ?>">
							<?php if ( $slide['title'] ) : ?>
								<h3 class="vert__title">
									<button class="vert__toggle" type="button" data-vert-go="<?php echo esc_attr( $index ); ?>" aria-expanded="<?php echo $active ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $body ); ?>">
										<?php echo esc_html( $slide['title'] ); ?>
										<i class="vert__sign" aria-hidden="true"></i>
									</button>
								</h3>
							<?php endif; ?>
							<div class="vert__body" id="<?php echo esc_attr( $body ); ?>">
								<div class="vert__inner">
									<?php if ( $slide['text'] ) : ?>
										<?php echo wp_kses_post( wpautop( $slide['text'] ) ); ?>
									<?php endif; ?>
									<?php if ( $slide['link'] ) : ?>
										<a class="btn btn--quiet vert__btn" href="<?php echo esc_url( $slide['link'] ); ?>" data-snake-arrow>
											<?php echo esc_html( $slide['label'] ); ?>
											<?php severus_arrow(); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="impact__stage">
				<canvas class="crystal" data-crystal data-slides="<?php echo esc_attr( wp_json_encode( $images ) ); ?>" aria-hidden="true"></canvas>
				<a class="impact__link" href="<?php echo esc_url( $first['link'] ?? home_url( '/' ) ); ?>" data-stage-link aria-label="<?php echo esc_attr( $first['label'] ?? '' ); ?>"></a>
				<div class="impact__ticks" aria-hidden="true">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<i<?php echo 0 === $index ? ' class="is-active"' : ''; ?>></i>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>
