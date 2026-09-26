<?php
/**
 * The top of a case study: the featured image as a wide plate, with the
 * title and the client facts laid over its lower edge. Without an image the
 * plate keeps its shape on the lit background the blank case cards use.
 *
 * $data:
 *   facts  the cases_fields.case_info group (client, country)
 *   id     the case
 */
defined( 'ABSPATH' ) || exit;

$id = (int) ( $data['id'] ?? get_the_ID() );
?>
<section class="case-hero">
	<div class="shell case-hero__in">
		<div class="case-hero__plate<?php echo has_post_thumbnail( $id ) ? '' : ' case-hero__plate--blank'; ?>">
			<?php if ( has_post_thumbnail( $id ) ) : ?>
				<?php echo get_the_post_thumbnail( $id, 'full', array( 'class' => 'case-hero__art', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			<?php endif; ?>

			<div class="case-hero__copy">
				<h1 class="case-hero__title reveal"><?php echo esc_html( get_the_title( $id ) ); ?></h1>
				<?php
				severus_component(
					'case-facts',
					array(
						'facts' => $data['facts'] ?? array(),
						'id'    => $id,
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
