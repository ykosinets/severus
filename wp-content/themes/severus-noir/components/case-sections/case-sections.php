<?php
/**
 * A case study's write-up, built from the case_sections flexible content
 * field:
 *
 *   columns  two columns of editor text (one filled takes the full width)
 *   text     one column of editor text across the full width
 *   divider  the footer's line (.site-foot::before) across the shell
 *   steps    a numbered ledger like the services' "How we approach it";
 *            the bullet is any text and counts up by itself when empty
 *   button   a link button, left, centred or right
 *
 * A section with nothing in it is skipped, and so is a divider with nothing
 * to separate — at either end, or next to another divider.
 *
 * $data:
 *   sections  rows of case_sections
 */
defined( 'ABSPATH' ) || exit;

/* Editor HTML counts when it has words or media — not just empty tags. */
$filled = static fn( $html ): bool => is_string( $html )
	&& ( '' !== trim( html_entity_decode( wp_strip_all_tags( $html ) ), " \t\n\r\0\x0B\xC2\xA0" ) || (bool) preg_match( '/<(img|iframe|video|table)\b/i', $html ) );

$sections = array();

foreach ( (array) ( $data['sections'] ?? array() ) as $row ) {
	if ( ! is_array( $row ) ) {
		continue;
	}

	switch ( $row['acf_fc_layout'] ?? '' ) {
		case 'columns':
			$columns = array_values( array_filter( array( $row['left'] ?? '', $row['right'] ?? '' ), $filled ) );

			if ( $columns ) {
				$sections[] = array( 'layout' => 'columns', 'columns' => $columns );
			}
			break;

		case 'text':
			if ( $filled( $row['content'] ?? '' ) ) {
				$sections[] = array( 'layout' => 'text', 'content' => $row['content'] );
			}
			break;

		case 'divider':
			$sections[] = array( 'layout' => 'divider' );
			break;

		case 'steps':
			$items = array_values( array_filter( (array) ( $row['items'] ?? array() ), static fn( $item ) => is_array( $item ) && $filled( $item['content'] ?? '' ) ) );

			if ( $items ) {
				$sections[] = array( 'layout' => 'steps', 'items' => $items );
			}
			break;

		case 'button':
			$link = $row['link'] ?? null;

			if ( is_array( $link ) && ! empty( $link['url'] ) && '' !== trim( (string) ( $link['title'] ?? '' ) ) ) {
				$sections[] = array(
					'layout' => 'button',
					'link'   => $link,
					'align'  => in_array( $row['align'] ?? '', array( 'left', 'center', 'right' ), true ) ? $row['align'] : 'left',
				);
			}
			break;

		case 'ticks':
			$items = array_values( array_filter( (array) ( $row['items'] ?? array() ), static fn( $item ) => is_array( $item ) && ( ! empty( $item['title'] ) || $filled( $item['text'] ?? '' ) ) ) );
			if ( $items ) { $sections[] = array( 'layout' => 'ticks', 'title' => $row['title'] ?? '', 'items' => $items ); }
			break;

		case 'table':
			if ( $filled( $row['content'] ?? '' ) ) { $sections[] = array( 'layout' => 'table', 'title' => $row['title'] ?? '', 'content' => $row['content'] ); }
			break;

		case 'media':
			if ( ! empty( $row['media'] ) ) { $sections[] = array( 'layout' => 'media', 'title' => $row['title'] ?? '', 'media' => severus_image( $row['media'] ), 'align' => $row['align'] ?? 'center' ); }
			break;
	}
}

/* A divider only separates: drop the ones at either end and doubled ones. */
$sections = array_values(
	array_filter(
		$sections,
		static fn( array $section, int $i ): bool => 'divider' !== $section['layout']
			|| ( $i > 0 && isset( $sections[ $i + 1 ] ) && 'divider' !== $sections[ $i + 1 ]['layout'] ),
		ARRAY_FILTER_USE_BOTH
	)
);

if ( ! $sections ) {
	return;
}
?>
<div class="case-sections is-narrow">
	<?php foreach ( $sections as $s ) : ?>
		<?php
		$classes = array( 'case-section', 'case-section--' . $s['layout'] );

		if ( 'columns' === $s['layout'] && 1 === count( $s['columns'] ) ) {
			$classes[] = 'case-section--single';
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo 'divider' === $s['layout'] ? ' aria-hidden="true"' : ''; ?>>
			<div class="shell case-section__in">
				<?php switch ( $s['layout'] ) :
					case 'columns': ?>
						<?php foreach ( $s['columns'] as $column ) : ?>
							<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $column ); ?></div>
						<?php endforeach; ?>
						<?php break;

					case 'text': ?>
						<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $s['content'] ); ?></div>
						<?php break;

					case 'divider': ?>
						<hr class="case-divider">
						<?php break;

					case 'steps': ?>
						<ol class="points__list case-steps">
							<?php foreach ( $s['items'] as $index => $item ) : ?>
								<?php $bullet = trim( (string) ( $item['bullet'] ?? '' ) ); ?>
								<li class="point rule reveal">
									<span class="point__n"><?php echo esc_html( '' !== $bullet ? $bullet : str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
									<div class="case-steps__text entry__body"><?php echo wp_kses_post( $item['content'] ); ?></div>
								</li>
							<?php endforeach; ?>
						</ol>
						<?php break;

					case 'button': ?>
						<div class="case-section__button case-section__button--<?php echo esc_attr( $s['align'] ); ?> reveal">
							<?php severus_button( $s['link'], 'btn btn--solid' ); ?>
						</div>
						<?php break;

					case 'ticks': ?>
						<?php if ( $s['title'] ) : ?><h2><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
						<ul class="gains__list"><?php foreach ( $s['items'] as $item ) : ?><li class="gain rule"><span class="gain__tick">✓</span><div><?php if ( ! empty( $item['title'] ) ) : ?><h3 class="gain__title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?><div class="gain__text entry__body"><?php echo wp_kses_post( $item['text'] ?? '' ); ?></div></div></li><?php endforeach; ?></ul>
						<?php break;

					case 'table': ?>
						<?php if ( $s['title'] ) : ?><h2><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?><div class="case-section__text entry__body"><?php echo wp_kses_post( $s['content'] ); ?></div>
						<?php break;

					case 'media': ?>
						<div class="case-section__media case-section__media--<?php echo esc_attr( $s['align'] ); ?>"><div class="entry__body"><?php echo wp_kses_post( $s['title'] ); ?></div><?php if ( $s['media'] ) : ?><img src="<?php echo esc_url( $s['media']['url'] ); ?>" alt="<?php echo esc_attr( $s['media']['alt'] ); ?>"><?php endif; ?></div>
						<?php break;
				endswitch; ?>
			</div>
		</section>
	<?php endforeach; ?>
</div>
