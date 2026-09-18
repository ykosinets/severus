<?php
/**
 * Client, categories and country of a case study.
 *
 * $data:
 *   facts  the cases_fields.case_info group (client, country)
 *   id     the case, for its case_category terms
 */
defined( 'ABSPATH' ) || exit;

$facts = (array) ( $data['facts'] ?? array() );
$terms = get_the_terms( $data['id'] ?? get_the_ID(), 'case_category' );
$rows  = array();

if ( ! empty( $facts['client'] ) ) {
	$rows[] = array( __( 'Client', 'severus-noir' ), esc_html( $facts['client'] ) );
}

if ( $terms && ! is_wp_error( $terms ) ) {
	$links = array_map(
		static fn( WP_Term $term ) => '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>',
		$terms
	);

	$rows[] = array( _n( 'Category', 'Categories', count( $terms ), 'severus-noir' ), implode( ', ', $links ) );
}

if ( ! empty( $facts['country'] ) ) {
	$rows[] = array( __( 'Country', 'severus-noir' ), esc_html( $facts['country'] ) );
}

if ( ! $rows ) {
	return;
}
?>
<dl class="facts">
	<?php foreach ( $rows as list( $label, $value ) ) : ?>
		<div class="facts__row">
			<dt><?php echo esc_html( $label ); ?></dt>
			<dd><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped above. ?></dd>
		</div>
	<?php endforeach; ?>
</dl>
