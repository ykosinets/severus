<?php
/**
 * Common questions. Native <details name> — an exclusive accordion, no JS.
 *
 * $data (all optional; the front page fields are the defaults):
 *   items               list of [question, answer]
 *   label, title, text  the side column
 *   id                  section id
 *   orbit               'left' | 'right' — the orbit mark over the top edge
 */
defined( 'ABSPATH' ) || exit;

$items = $data['items'] ?? null;

if ( null === $items ) {
	$items = array();

	foreach ( (array) get_field( 'faq_list' ) as $row ) {
		$items[] = array(
			'question' => $row['faq_item_title'] ?? '',
			'answer'   => $row['faq_item_descr'] ?? '',
		);
	}
}

$items = array_filter( (array) $items, static fn( $item ) => ! empty( $item['question'] ) );

if ( ! $items ) {
	return;
}

$label = $data['label'] ?? '';
$title = $data['title'] ?? get_field( 'faq_title' );
$text  = $data['text'] ?? '';
$group = 'severus-faq-' . wp_unique_id();
$index = 0;
?>
<section class="section faq" id="<?php echo esc_attr( $data['id'] ?? 'faq' ); ?>">
	<?php if ( ! empty( $data['orbit'] ) ) : ?>
		<?php severus_orbit( 'orbit--deco orbit--' . $data['orbit'], 'page' ); ?>
	<?php endif; ?>
	<div class="shell faq__in">

		<div class="faq__side">
			<?php if ( $label ) : ?>
				<p class="eyebrow"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h2 class="faq__title is-display"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="faq__text"><?php echo wp_kses_post( $text ); ?></p>
			<?php endif; ?>
		</div>

		<div class="faq__list">
			<?php foreach ( $items as $item ) : ?>
				<details class="ask rule" name="<?php echo esc_attr( $group ); ?>"<?php echo 0 === $index ? ' open' : ''; ?>>
					<summary>
						<?php echo esc_html( wp_strip_all_tags( $item['question'] ) ); ?>
						<span class="ask__sign" aria-hidden="true"></span>
					</summary>
					<?php if ( ! empty( $item['answer'] ) ) : ?>
						<div class="ask__body"><?php echo wp_kses_post( wpautop( $item['answer'] ) ); ?></div>
					<?php endif; ?>
				</details>
				<?php
				$index++;
			endforeach;
			?>
		</div>

	</div>
</section>
