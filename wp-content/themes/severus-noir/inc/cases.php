<?php
/**
 * Public case archive filtering and pagination.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve a submitted category slug to a real case category.
 */
function severus_case_category( string $slug ): ?WP_Term {
	if ( '' === $slug || 'all' === $slug ) {
		return null;
	}

	$term = get_term_by( 'slug', sanitize_title( $slug ), 'case_category' );

	return $term instanceof WP_Term ? $term : null;
}

/**
 * Query case studies for the archive and its filters.
 *
 * @return array<string,mixed>
 */
function severus_case_query_args( ?WP_Term $category, int $page ): array {
	$args = array(
		'post_type'      => 'case',
		'post_status'    => 'publish',
		'posts_per_page' => (int) get_option( 'posts_per_page' ),
		'paged'          => max( 1, $page ),
	);

	if ( $category ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'case_category',
				'field'    => 'term_id',
				'terms'    => $category->term_id,
			),
		);
	}

	return $args;
}

/** Render only the changing part of the case archive. */
function severus_render_case_results( WP_Query $query ): string {
	ob_start();

	if ( $query->have_posts() ) :
		?>
		<div class="works">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				severus_work_card( get_the_ID() );
			endwhile;
			?>
		</div>
		<?php
	else :
		?>
		<p><?php esc_html_e( 'No cases yet.', 'severus-noir' ); ?></p>
		<?php
	endif;

	wp_reset_postdata();

	return (string) ob_get_clean();
}

/** URL that represents an AJAX-selected archive state. */
function severus_case_archive_url( ?WP_Term $category, int $page ): string {
	$base = $category ? get_term_link( $category ) : get_post_type_archive_link( 'case' );

	if ( is_wp_error( $base ) || $page < 2 ) {
		return is_wp_error( $base ) ? home_url( '/cases/' ) : (string) $base;
	}

	return trailingslashit( trailingslashit( (string) $base ) . 'page/' . $page );
}

/** Render JavaScript-only pager controls for an AJAX response. */
function severus_render_case_ajax_pagination( WP_Query $query ): string {
	if ( $query->max_num_pages < 2 ) {
		return '';
	}

	$current = max( 1, (int) $query->get( 'paged' ) );
	$total   = (int) $query->max_num_pages;
	$pages   = array_unique( array_filter( array( 1, $current - 1, $current, $current + 1, $total ), static fn( int $page ): bool => $page > 0 && $page <= $total ) );
	sort( $pages );

	ob_start();
	?>
	<nav class="navigation pagination pager" aria-label="<?php esc_attr_e( 'Cases pagination', 'severus-noir' ); ?>">
		<div class="nav-links">
			<?php if ( $current > 1 ) : ?>
				<button class="prev page-numbers" type="button" data-case-page="<?php echo $current - 1; ?>"><?php esc_html_e( 'Previous', 'severus-noir' ); ?></button>
			<?php endif; ?>
			<?php $previous = 0; ?>
			<?php foreach ( $pages as $page ) : ?>
				<?php if ( $previous && $page > $previous + 1 ) : ?>
					<span class="page-numbers dots"><?php esc_html_e( '…', 'severus-noir' ); ?></span>
				<?php endif; ?>
				<button class="page-numbers<?php echo $page === $current ? ' current' : ''; ?>" type="button" data-case-page="<?php echo $page; ?>"<?php echo $page === $current ? ' aria-current="page"' : ''; ?>><?php echo $page; ?></button>
				<?php $previous = $page; ?>
			<?php endforeach; ?>
			<?php if ( $current < $total ) : ?>
				<button class="next page-numbers" type="button" data-case-page="<?php echo $current + 1; ?>"><?php esc_html_e( 'Next', 'severus-noir' ); ?></button>
			<?php endif; ?>
		</div>
	</nav>
	<?php

	return (string) ob_get_clean();
}

/** Return filtered cases without navigating away from the archive. */
function severus_ajax_filter_cases(): void {
	$category = severus_case_category( sanitize_text_field( wp_unslash( $_POST['category'] ?? '' ) ) );
	$page     = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$query    = new WP_Query( severus_case_query_args( $category, $page ) );

	wp_send_json_success(
		array(
			'results'    => severus_render_case_results( $query ),
			'pagination' => severus_render_case_ajax_pagination( $query ),
			'url'        => severus_case_archive_url( $category, max( 1, $page ) ),
		)
	);
}
add_action( 'wp_ajax_severus_filter_cases', 'severus_ajax_filter_cases' );
add_action( 'wp_ajax_nopriv_severus_filter_cases', 'severus_ajax_filter_cases' );
