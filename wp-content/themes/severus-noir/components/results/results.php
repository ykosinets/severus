<?php
/**
 * Case studies: three picture cards in a row. More than three turn the row
 * into a sideways rail that ends in a link to the cases archive.
 *
 * $data (all optional; the front page fields are the defaults):
 *   cases               post ids or objects
 *   label, title, text  the header
 *   more                ACF link shown under the cards
 *   id                  section id
 *   orbit               'left' | 'right' — the orbit mark over the top edge
 */
defined( 'ABSPATH' ) || exit;

$cases = severus_ids( $data['cases'] ?? get_field( 'case-list' ) );

if ( ! $cases ) {
	return;
}

$rail = count( $cases ) > 3;

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

		<div class="works<?php echo $rail ? ' works--rail' : ''; ?>">
			<?php
			foreach ( $cases as $id ) {
				severus_work_card( $id );
			}
			?>
			<?php if ( $rail ) : ?>
				<a class="works__more" href="<?php echo esc_url( get_post_type_archive_link( 'case' ) ); ?>" data-snake-arrow>
					<span class="works__more-go" aria-hidden="true"><?php severus_arrow(); ?></span>
					<span class="works__more-label"><?php esc_html_e( 'More cases', 'severus-noir' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $data['more'] ) ) : ?>
			<div class="results__more">
				<?php severus_button( $data['more'], 'link' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
