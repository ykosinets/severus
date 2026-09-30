<?php
/**
 * A single post or custom post type entry.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'section entry' ); ?>>
		<div class="shell is-narrow">
			<header class="entry__head">
				<h1 class="entry__title"><?php the_title(); ?></h1>
				<p class="entry__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry__art"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>

			<?php $sections = get_field( 'blog_sections' ); ?>
			<?php if ( $sections ) : ?>
				<?php severus_component( 'blog-sections', array( 'sections' => $sections ) ); ?>
			<?php else : ?>
				<div class="entry__body"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
