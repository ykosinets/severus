<?php
/**
 * Archive fallback.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="section is-narrow"<?php if ( is_home() ) : ?> data-blog id="blog-posts"<?php endif; ?>>
	<div class="shell">
		<header class="lead reveal">
			<h1 class="lead__title"><?php echo esc_html( wp_get_document_title() ); ?></h1>
		</header>

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
