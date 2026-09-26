<?php
/**
 * A case study's write-up, a section at a time: the title on one side, the
 * text on the other, a line between sections. is_reverse puts the text on
 * the left. The title stays first in the markup either way.
 *
 * $data:
 *   sections  rows of the case_sections repeater (title, content, is_reverse)
 */
defined( 'ABSPATH' ) || exit;

$sections = array_filter(
	(array) ( $data['sections'] ?? array() ),
	static fn( $row ) => is_array( $row ) && ( '' !== trim( (string) ( $row['title'] ?? '' ) ) || '' !== trim( (string) ( $row['content'] ?? '' ) ) )
);

if ( ! $sections ) {
	return;
}
?>
<div class="case-sections is-narrow">
	<?php foreach ( $sections as $section ) : ?>
		<section class="case-section<?php echo empty( $section['is_reverse'] ) ? '' : ' case-section--reverse'; ?>">
			<div class="shell case-section__in">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="case-section__title reveal"><?php echo esc_html( $section['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['content'] ) ) : ?>
					<div class="case-section__text entry__body reveal"><?php echo wp_kses_post( $section['content'] ); ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php endforeach; ?>
</div>
