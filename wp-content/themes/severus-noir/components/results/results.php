<?php
/**
 * Case studies: three picture cards in a row.
 *
 * $data (all optional; the front page fields are the defaults):
 *   cases               post ids or objects; only the first three are shown
 *   label, title, text  the header
 *   more                ACF link shown under the cards
 *   id                  section id
 *   orbit               'left' | 'right' — the orbit mark over the top edge
 */
defined( 'ABSPATH' ) || exit;

$cases = array_slice( severus_ids( $data['cases'] ?? get_field( 'case-list' ) ), 0, 3 );

if ( ! $cases ) {
	return;
}

$lead = array(
	'label' => $data['label'] ?? '',
	'title' => $data['title'] ?? get_field( 'results_title' ),
	'text'  => $data['text'] ?? get_field( 'results_subtitle' ),
);
?>
<section class="section results" id="<?php echo esc_attr( $data['id'] ?? 'results' ); ?>">
	<?php if ( ! empty( $data['orbit'] ) ) : ?>
		<?php severus_orbit( 'orbit--deco orbit--' . $data['orbit'], 'page' ); ?>
	<?php endif; ?>
	<div class="shell">
		<?php severus_lead( $lead, array( 'split' => true ) ); ?>

		<div class="works">
			<?php
			foreach ( $cases as $id ) {
				severus_work_card( $id );
			}
			?>
		</div>

		<?php if ( ! empty( $data['more'] ) ) : ?>
			<div class="results__more">
				<?php severus_button( $data['more'], 'link' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
