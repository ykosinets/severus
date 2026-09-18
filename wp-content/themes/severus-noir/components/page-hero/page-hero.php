<?php
/**
 * The top of an inner page: eyebrow, the h1, a subtitle, a lede,
 * a call to action and, when there is one, an image beside them.
 *
 * $data:
 *   label     eyebrow
 *   title     h1 (accents allowed); defaults to the post title
 *   subtitle  a larger line under the title
 *   text      the lede (HTML allowed)
 *   button    ACF link
 *   image     from severus_image()
 *   art       'framed' (a square plate, for icons) or 'open' (default)
 *   wide      true to use the full shell (inner pages default to the narrow one)
 *   orbit     'left' (default without an image), 'right', or '' for none
 *   center    true to centre the title and text
 */
defined( 'ABSPATH' ) || exit;

$title    = ( $data['title'] ?? '' ) ?: get_the_title();
$image    = $data['image'] ?? null;
$art      = $data['art'] ?? 'open';
$subtitle = $data['subtitle'] ?? '';
$text     = $data['text'] ?? '';
$orbit    = $data['orbit'] ?? ( $image ? '' : 'left' );
?>
<section class="page-hero<?php echo $image ? ' page-hero--split' : ''; ?><?php echo empty( $data['center'] ) ? '' : ' page-hero--center'; ?><?php echo empty( $data['wide'] ) ? ' is-narrow' : ''; ?>">
	<?php if ( $orbit ) : ?>
		<?php severus_orbit( 'orbit--deco orbit--' . $orbit, 'page' ); ?>
	<?php endif; ?>

	<div class="shell page-hero__in">
		<div class="page-hero__copy">

			<?php if ( ! empty( $data['label'] ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $data['label'] ); ?></p>
			<?php endif; ?>

			<h1 class="page-hero__title reveal"><?php echo wp_kses_post( $title ); ?></h1>

			<?php if ( $subtitle ) : ?>
				<p class="page-hero__sub reveal"><?php echo wp_kses_post( $subtitle ); ?></p>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<div class="page-hero__text reveal"><?php echo wp_kses_post( false !== strpos( $text, '<p' ) ? $text : wpautop( $text ) ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $data['button'] ) ) : ?>
				<div class="page-hero__actions reveal">
					<?php severus_button( $data['button'], 'btn btn--solid' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $image ) : ?>
			<figure class="page-hero__art page-hero__art--<?php echo esc_attr( $art ); ?><?php echo 'framed' === $art ? ' edge' : ''; ?> reveal">
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>" fetchpriority="high">
			</figure>
		<?php endif; ?>
	</div>
</section>
