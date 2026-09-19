<?php
/**
 * Why companies trust Severus. The big figures count up from zero.
 */
defined( 'ABSPATH' ) || exit;

$title = severus_field( 'trust_title' );

$big   = severus_rows( 'trust_stats_lg' );
$small = severus_rows( 'trust_stats_sm' );

if ( ! $title && ! $big ) {
	return;
}

$mark = severus_image( severus_field( 'trust_logo' ) );
?>
<section class="section section--warm is-narrow trust">
	<?php if ( $mark ) : ?>
		<img class="trust__mark" src="<?php echo esc_url( $mark['url'] ); ?>" alt="" aria-hidden="true" loading="lazy">
	<?php endif; ?>

	<div class="shell">

		<div class="trust__head">
			<?php if ( $title ) : ?>
                <div class="reveal is-display">
                    <h2 class="trust__title"><?php echo wp_kses_post( $title ); ?></h2>
                    <?php if ( $description = severus_field( 'trust_descr' ) ) : ?>
                        <p class="trust__description"><?php echo wp_kses_post( $description ); ?></p>
                    <?php endif; ?>
                </div>
			<?php endif; ?>

			<div class="trust__aside reveal">
				<?php severus_button( severus_field( 'trust_button' ), 'btn btn--solid' ); ?>
			</div>
		</div>

		<div class="figures">
			<?php if ( $big ) : ?>
				<div class="figures__big">
					<?php
					foreach ( $big as $figure ) :
						$number = severus_split_number( $figure['stlg_number'] ?? '' );
						?>
						<div class="figure rule">
							<b class="figure__n">
								<span class="odo" data-odo="<?php echo esc_attr( $number['count'] ); ?>"></span>
								<?php if ( $number['suffix'] ) : ?>
									<i><?php echo esc_html( $number['suffix'] ); ?></i>
								<?php endif; ?>
							</b>
							<?php if ( $text = ( $figure['stlg_text'] ?? '' ) ) : ?>
								<p><?php echo wp_kses_post( $text ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $small ) : ?>
				<div class="figures__small">
					<?php foreach ( $small as $note ) : ?>
						<p class="figure__note reveal is-bright"><?php echo wp_kses_post( $note['stsm_text'] ?? '' ); ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
