<?php
/** Render structured blog sections. @package Severus_Noir */
defined( 'ABSPATH' ) || exit;
$sections = (array) ( $data['sections'] ?? array() );
$bullets = array( 'tick' => '✓', 'cross' => '×', 'arrow' => '→', 'plus' => '+', 'dash' => '—' );
foreach ( $sections as $section ) : $layout = $section['acf_fc_layout'] ?? 'copy'; $bullet = $bullets[ $section['bullet'] ?? 'tick' ] ?? $bullets['tick']; $icon_color = in_array( $section['icon_color'] ?? '', array( 'neutral', 'green', 'red' ), true ) ? $section['icon_color'] : 'green'; ?>
	<section class="section blog-section blog-section--<?php echo esc_attr( $layout ); ?>"><div class="shell is-narrow">
		<?php if ( 'copy' === $layout ) : ?><div class="blog-section__grid"><h2><?php echo esc_html( $section['title'] ?? '' ); ?></h2><div class="entry__body"><?php echo wp_kses_post( $section['content'] ?? '' ); ?></div></div>
		<?php elseif ( 'full' === $layout ) : ?><div class="entry__body blog-section__full"><?php echo wp_kses_post( $section['content'] ?? '' ); ?></div>
		<?php elseif ( 'table' === $layout ) : ?><div class="blog-section__table"><h2><?php echo esc_html( $section['title'] ?? '' ); ?></h2><div class="entry__body"><?php echo wp_kses_post( $section['content'] ?? '' ); ?></div></div>
		<?php elseif ( 'media' === $layout ) : ?><div class="blog-section__media blog-section__media--<?php echo esc_attr( $section['align'] ?? 'center' ); ?>"><div class="entry__body"><?php echo wp_kses_post( $section['title'] ?? '' ); ?></div><?php $media = severus_image( $section['media'] ?? null ); if ( $media ) : ?><img src="<?php echo esc_url( $media['url'] ); ?>" alt="<?php echo esc_attr( $media['alt'] ); ?>"><?php endif; ?></div>
		<?php elseif ( 'button' === $layout ) : ?><div class="blog-section__button blog-section__button--<?php echo esc_attr( $section['align'] ?? 'left' ); ?>"><?php severus_button( $section['link'] ?? null ); ?></div>
		<?php elseif ( in_array( $layout, array( 'numbered', 'gains' ), true ) ) : ?><h2><?php echo esc_html( $section['title'] ?? '' ); ?></h2><ol class="blog-section__items<?php echo 'gains' === $layout ? ' blog-section__items--ticks blog-section__items--' . esc_attr( $icon_color ) . ( 'none' === ( $section['bullet'] ?? '' ) ? ' blog-section__items--no-icon' : '' ) : ''; ?>"><?php foreach ( (array) ( $section['items'] ?? array() ) as $index => $item ) : ?><li><?php if ( 'gains' !== $layout || 'none' !== ( $section['bullet'] ?? '' ) ) : ?><span><?php echo 'gains' === $layout ? esc_html( $bullet ) : esc_html( $item['bullet'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><?php endif; ?><div><?php if ( ! empty( $item['title'] ) ) : ?><h3><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?><?php echo wp_kses_post( $item['text'] ?? '' ); ?></div></li><?php endforeach; ?></ol>
		<?php elseif ( 'divider' === $layout ) : ?><hr class="case-divider" aria-hidden="true">
		<?php endif; ?>
	</div></section>
<?php endforeach; ?>
