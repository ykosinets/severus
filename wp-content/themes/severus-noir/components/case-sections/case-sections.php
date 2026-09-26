<?php
/**
 * A case study's write-up, built from the case_sections flexible content
 * field, with the footer's line between sections:
 *
 *   title_text  title and text side by side (is_reverse: text on the left)
 *   media_text  image and text side by side (is_reverse: text on the left)
 *   text        text across the full width
 *   image       an image, full width or centred / left / right
 *   cards       title and text, then cards three to a row
 *   related     picked services, industries, cases or articles as link cards
 *   button      a link button, left, centred or right
 *
 * A section with nothing in it is skipped; a two-column one with only one
 * side filled takes the full width. Titles stay first in the markup however
 * the columns are ordered.
 *
 * $data:
 *   sections  rows of case_sections
 */
defined( 'ABSPATH' ) || exit;

/* Editor HTML counts when it has words or media — not just empty tags. */
$filled = static fn( $html ): bool => is_string( $html )
	&& ( '' !== trim( html_entity_decode( wp_strip_all_tags( $html ) ), " \t\n\r\0\x0B\xC2\xA0" ) || (bool) preg_match( '/<(img|iframe|video|table)\b/i', $html ) );

$image = static fn( $id, string $size, string $sizes ): string => $id
	? wp_get_attachment_image( (int) $id, $size, false, array( 'loading' => 'lazy', 'sizes' => $sizes ) )
	: '';

$sections = array();

foreach ( (array) ( $data['sections'] ?? array() ) as $row ) {
	if ( ! is_array( $row ) ) {
		continue;
	}

	$layout  = $row['acf_fc_layout'] ?? '';
	$title   = trim( (string) ( $row['title'] ?? '' ) );
	$content = $filled( $row['content'] ?? '' ) ? $row['content'] : '';
	$img     = $image( $row['image'] ?? 0, 'image' === $layout && 'full' === ( $row['align'] ?? 'full' ) ? 'full' : 'large', '(min-width: 1024px) 60vw, 100vw' );

	switch ( $layout ) {
		case 'title_text':
			$keep = $title || $content;
			break;
		case 'media_text':
			$keep = $img || $content;
			break;
		case 'text':
			$keep = (bool) $content;
			break;
		case 'image':
			$keep = (bool) $img;
			break;
		case 'cards':
			$row['cards'] = array_values( array_filter( (array) ( $row['cards'] ?? array() ), static fn( $card ) => is_array( $card ) && $filled( $card['content'] ?? '' ) ) );
			$keep         = $title || $content || $row['cards'];
			break;
		case 'related':
			$row['items'] = severus_ids( $row['items'] ?? array() );
			$title        = $title ?: __( 'Related', 'severus-noir' );
			$keep         = (bool) $row['items'];
			break;
		case 'button':
			$keep = is_array( $row['link'] ?? null ) && ! empty( $row['link']['url'] ) && '' !== trim( (string) ( $row['link']['title'] ?? '' ) );
			break;
		default:
			$keep = false;
	}

	if ( $keep ) {
		$sections[] = compact( 'layout', 'title', 'content', 'img' ) + array(
			'reverse' => ! empty( $row['is_reverse'] ),
			'align'   => in_array( $row['align'] ?? '', array( 'full', 'center', 'left', 'right' ), true ) ? $row['align'] : ( 'button' === $layout ? 'left' : 'full' ),
			'cards'   => $row['cards'] ?? array(),
			'items'   => $row['items'] ?? array(),
			'link'    => $row['link'] ?? null,
		);
	}
}

if ( ! $sections ) {
	return;
}
?>
<div class="case-sections is-narrow">
	<?php foreach ( $sections as $s ) : ?>
		<?php
		$pair    = in_array( $s['layout'], array( 'title_text', 'media_text' ), true );
		$lead    = 'media_text' === $s['layout'] ? $s['img'] : $s['title'];
		$classes = array( 'case-section', 'case-section--' . str_replace( '_', '-', $s['layout'] ) );

		if ( $pair && $s['reverse'] ) {
			$classes[] = 'case-section--reverse';
		}

		if ( $pair && ( ! $lead || ! $s['content'] ) ) {
			$classes[] = 'case-section--single';
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="shell case-section__in">
				<?php switch ( $s['layout'] ) :
					case 'title_text': ?>
						<?php if ( $s['title'] ) : ?>
							<h2 class="case-section__title reveal"><?php echo esc_html( $s['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( $s['content'] ) : ?>
							<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $s['content'] ); ?></div>
						<?php endif; ?>
						<?php break;

					case 'media_text': ?>
						<?php if ( $s['img'] ) : ?>
							<figure class="case-section__media reveal"><?php echo $s['img']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — core markup. ?></figure>
						<?php endif; ?>
						<?php if ( $s['content'] ) : ?>
							<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $s['content'] ); ?></div>
						<?php endif; ?>
						<?php break;

					case 'text': ?>
						<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $s['content'] ); ?></div>
						<?php break;

					case 'image': ?>
						<figure class="case-section__figure case-section__figure--<?php echo esc_attr( $s['align'] ); ?> reveal"><?php echo $s['img']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — core markup. ?></figure>
						<?php break;

					case 'cards': ?>
						<?php if ( $s['title'] || $s['content'] ) : ?>
							<div class="case-section__head">
								<?php if ( $s['title'] ) : ?>
									<h2 class="case-section__title reveal"><?php echo esc_html( $s['title'] ); ?></h2>
								<?php endif; ?>
								<?php if ( $s['content'] ) : ?>
									<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $s['content'] ); ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $s['cards'] ) : ?>
							<ul class="case-cards">
								<?php foreach ( $s['cards'] as $card ) : ?>
									<li class="case-card edge reveal">
										<div class="entry__body"><?php echo wp_kses_post( $card['content'] ); ?></div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php break;

					case 'related': ?>
						<h2 class="case-section__title case-section__title--small reveal"><?php echo esc_html( $s['title'] ); ?></h2>
						<ul class="case-links">
							<?php foreach ( $s['items'] as $item ) : ?>
								<?php
								$type = get_post_type( $item );
								$kind = 'post' === $type ? __( 'Article', 'severus-noir' ) : ( get_post_type_object( $type )->labels->singular_name ?? '' );
								?>
								<li class="reveal">
									<a class="case-link edge" href="<?php echo esc_url( get_permalink( $item ) ); ?>" data-snake-arrow>
										<span class="case-link__kind"><?php echo esc_html( $kind ); ?></span>
										<span class="case-link__title"><?php echo esc_html( get_the_title( $item ) ); ?></span>
										<span class="case-link__go" aria-hidden="true"><?php severus_arrow(); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php break;

					case 'button': ?>
						<div class="case-section__button case-section__button--<?php echo esc_attr( $s['align'] ); ?> reveal">
							<?php severus_button( $s['link'], 'btn btn--solid' ); ?>
						</div>
						<?php break;
				endswitch; ?>
			</div>
		</section>
	<?php endforeach; ?>
</div>
