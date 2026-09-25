<?php
/**
 * All case studies, and a single case category (taxonomy-case_category.php
 * loads this file). The heading copy for the full list lives in the ACF
 * options page, as before.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

$term    = is_tax( 'case_category' ) ? get_queried_object() : null;
$archive = (string) get_post_type_archive_link( 'case' );
$all     = post_type_archive_title( '', false ) ?: __( 'Cases', 'severus-noir' );

severus_component(
	'page-hero',
	$term
		? array(
			'orbit' => 'right',
			'title' => $term->name,
			'text'  => term_description( $term ),
		)
		: array(
			'orbit'    => 'right',
			'title'    => get_field( 'case-list-title', 'option' ) ?: $all,
			'subtitle' => get_field( 'case-list-subtitle', 'option' ),
			'text'     => get_field( 'case-list-description', 'option' ),
		)
);

$categories = get_terms( array( 'taxonomy' => 'case_category', 'hide_empty' => true ) );
?>

<section class="section cases" id="case-studies" data-cases data-cases-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
	<div class="shell">
		<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
			<nav class="filter" aria-label="<?php esc_attr_e( 'Filter cases by category', 'severus-noir' ); ?>">
				<ul class="filter__list">
					<li>
						<a class="btn <?php echo $term ? 'btn--quiet' : 'btn--solid'; ?>" href="<?php echo esc_url( $archive ); ?>" data-cases-filter data-case-category="all"<?php echo $term ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'severus-noir' ); ?></a>
					</li>
					<?php foreach ( $categories as $category ) : ?>
						<?php $current = $term && $term->term_id === $category->term_id; ?>
						<li>
							<a class="btn <?php echo $current ? 'btn--solid' : 'btn--quiet'; ?>" href="<?php echo esc_url( get_term_link( $category ) ); ?>" data-cases-filter data-case-category="<?php echo esc_attr( $category->slug ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $category->name ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<div data-cases-results aria-live="polite">
			<?php if ( have_posts() ) : ?>
				<div class="works">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php severus_work_card( get_the_ID() ); ?>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'No cases yet.', 'severus-noir' ); ?></p>
			<?php endif; ?>
		</div>

		<div data-cases-pagination>
			<?php the_posts_pagination( array( 'class' => 'pager', 'mid_size' => 1, 'prev_text' => __( 'Previous', 'severus-noir' ), 'next_text' => __( 'Next', 'severus-noir' ) ) ); ?>
		</div>
	</div>
</section>

<?php
severus_component( 'callout', severus_get_started() );

get_footer();
