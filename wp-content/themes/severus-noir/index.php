<?php
/**
 * Archive fallback.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

/* The blog and its category archives share one page: the category buttons
   are plain links to the archives, and blog.js swaps the posts in place. */
$blog = is_home() || is_category();

if ( $blog ) {
	$blog_categories = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
		)
	);
	$blog_term       = is_category() ? get_queried_object() : null;
}
?>

<section class="section is-narrow"<?php if ( $blog ) : ?> data-blog id="blog-posts"<?php endif; ?>>
	<div class="shell">
		<header class="lead reveal">
			<h1 class="lead__title"><?php echo esc_html( wp_get_document_title() ); ?></h1>
		</header>

		<?php if ( $blog && $blog_categories && ! is_wp_error( $blog_categories ) ) : ?>
			<nav class="filter" aria-label="<?php esc_attr_e( 'Filter posts by category', 'severus-noir' ); ?>">
				<ul class="filter__list">
					<li>
						<a class="btn <?php echo $blog_term ? 'btn--quiet' : 'btn--solid'; ?>" href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>" data-blog-filter="all"<?php echo $blog_term ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'severus-noir' ); ?></a>
					</li>
					<?php foreach ( $blog_categories as $blog_category ) : ?>
						<?php $current = $blog_term && $blog_term->term_id === $blog_category->term_id; ?>
						<li>
							<a class="btn <?php echo $current ? 'btn--solid' : 'btn--quiet'; ?>" href="<?php echo esc_url( get_term_link( $blog_category ) ); ?>" data-blog-filter="<?php echo esc_attr( $blog_category->slug ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $blog_category->name ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<div data-blog-content aria-live="polite" tabindex="-1">
		<?php if ( have_posts() ) : ?>
			<div class="journal__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<a class="post reveal" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<span class="post__art"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></span>
						<?php endif; ?>
						<h2 class="post__title"><?php the_title(); ?></h2>
						<span class="post__meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						</span>
					</a>
				<?php endwhile; ?>
			</div>

			<?php the_posts_pagination( array( 'class' => 'pager' ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing here yet.', 'severus-noir' ); ?></p>
		<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
