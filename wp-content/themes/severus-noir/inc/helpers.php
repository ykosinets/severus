<?php
/**
 * Template helpers shared by the components.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a component template.
 *
 * @param string              $name Folder name under components/.
 * @param array<string,mixed> $data Values exposed to the template as $data.
 */
function severus_component( string $name, array $data = array() ): void {
	$file = get_theme_file_path( "components/{$name}/{$name}.php" );

	if ( ! file_exists( $file ) ) {
		return;
	}

	include $file;
}

/**
 * The theme's arrow. Four copies sit round a small drum — printed on its side,
 * the axis upright — and while hovered the drum spins, arrows rolling off to
 * the right as the next ones come round from the left
 * (src/scripts/snake-arrow.js). The class keeps its old name so every place
 * that sizes .snake-arrow still does.
 */
function severus_arrow(): void {
	?>
	<span class="snake-arrow" aria-hidden="true">
		<span class="snake-arrow__drum">
			<?php for ( $face = 0; $face < 4; $face++ ) : ?>
				<svg class="snake-arrow__face" style="--face: <?php echo (int) $face; ?>" viewBox="0 0 19 15" fill="none" focusable="false">
					<path d="M0 6.36397 H17.5 V8.36397 H0 Z" fill="currentColor" />
					<path d="M11.13604 1.00001 L17.5 7.36397 L11.13604 13.72793" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			<?php endfor; ?>
		</span>
	</span>
	<?php
}

/**
 * Normalise an ACF image field to url/alt/width/height.
 *
 * @param mixed $field Attachment array, id or url.
 * @return array<string,mixed>|null
 */
function severus_image( $field ): ?array {
	if ( empty( $field ) ) {
		return null;
	}

	if ( is_array( $field ) ) {
		return array(
			'url'    => $field['url'] ?? '',
			'alt'    => $field['alt'] ?? '',
			'width'  => $field['width'] ?? '',
			'height' => $field['height'] ?? '',
		);
	}

	if ( is_numeric( $field ) ) {
		$src = wp_get_attachment_image_src( (int) $field, 'full' );

		if ( ! $src ) {
			return null;
		}

		return array(
			'url'    => $src[0],
			'alt'    => (string) get_post_meta( (int) $field, '_wp_attachment_image_alt', true ),
			'width'  => $src[1],
			'height' => $src[2],
		);
	}

	return array( 'url' => (string) $field, 'alt' => '', 'width' => '', 'height' => '' );
}

/**
 * Pull a plain URL out of an ACF file field.
 *
 * @param mixed $field File array, id or url.
 */
function severus_file_url( $field ): string {
	if ( empty( $field ) ) {
		return '';
	}

	if ( is_array( $field ) ) {
		return (string) ( $field['url'] ?? '' );
	}

	if ( is_numeric( $field ) ) {
		return (string) wp_get_attachment_url( (int) $field );
	}

	return (string) $field;
}

/**
 * Render an ACF link field as a button with the snake arrow.
 *
 * @param mixed  $link  ACF link array.
 * @param string $class Button classes.
 */
function severus_button( $link, string $class = 'btn btn--solid' ): void {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return;
	}

	$target = $link['target'] ?? '';
	?>
	<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $link['url'] ); ?>"
		<?php echo $target ? ' target="' . esc_attr( $target ) . '" rel="noopener"' : ''; ?>
		data-snake-arrow>
		<?php echo esc_html( $link['title'] ?? '' ); ?>
		<?php severus_arrow(); ?>
	</a>
	<?php
}

/**
 * Split a stat like "12+" into the number to count up to and its suffix.
 *
 * @return array{count:int,suffix:string}
 */
function severus_split_number( $value ): array {
	$value = trim( wp_strip_all_tags( (string) $value ) );

	if ( ! preg_match( '/^(\d+)(.*)$/u', $value, $matches ) ) {
		return array( 'count' => 0, 'suffix' => $value );
	}

	return array( 'count' => (int) $matches[1], 'suffix' => trim( $matches[2] ) );
}

/**
 * Where the "Contact us" calls to action point.
 */
function severus_contact_url(): string {
	$page = get_page_by_path( 'contact-us' );

	return $page ? get_permalink( $page ) : home_url( '/contact-us/' );
}

/**
 * Public contact address, overridable in the customizer.
 */
function severus_contact_email(): string {
	return (string) get_theme_mod( 'severus_email', get_option( 'admin_email' ) );
}

/**
 * The label a footer menu column is titled with.
 */
function severus_menu_title( string $location ): string {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return '';
	}

	$menu = wp_get_nav_menu_object( $locations[ $location ] );

	return $menu ? $menu->name : '';
}

/**
 * The image PhotoSwipe flies into the middle.
 *
 * The declared size has to match the file or PhotoSwipe corrects it after load
 * and the zoom lands with a jolt, so a theme-supplied still (cut to the video's
 * aspect) is preferred and the poster is the fallback.
 *
 * @param array<string,mixed>|null $poster Result of severus_image().
 * @return array{url:string,width:int,height:int}
 */
function severus_lightbox_still( ?array $poster ): array {
	$path = get_theme_file_path( 'assets/media/hero-still.webp' );

	if ( file_exists( $path ) ) {
		$size = getimagesize( $path );

		if ( $size ) {
			return array(
				'url'    => get_theme_file_uri( 'assets/media/hero-still.webp' ),
				'width'  => (int) $size[0],
				'height' => (int) $size[1],
			);
		}
	}

	return array(
		'url'    => $poster['url'] ?? '',
		'width'  => (int) ( $poster['width'] ?? 0 ),
		'height' => (int) ( $poster['height'] ?? 0 ),
	);
}

/**
 * Customizer settings the templates read.
 */
function severus_customize( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'severus_contact',
		array(
			'title'    => __( 'Severus — contact', 'severus-noir' ),
			'priority' => 130,
		)
	);

	$fields = array(
		'severus_email'        => array( __( 'Public email', 'severus-noir' ), get_option( 'admin_email' ), 'sanitize_email' ),
		'severus_linkedin'     => array( __( 'LinkedIn URL', 'severus-noir' ), '', 'esc_url_raw' ),
	);

	foreach ( $fields as $id => list( $label, $default, $sanitize ) ) {
		$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => $sanitize ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'severus_contact', 'type' => 'text' ) );
	}
}
add_action( 'customize_register', 'severus_customize' );

/**
 * A section header: optional eyebrow, the title and a line of text.
 *
 * @param array<string,mixed> $data  label, title, text.
 * @param array<string,mixed> $opts  tag (heading element), split (two columns), class.
 */
function severus_lead( array $data, array $opts = array() ): void {
	$label = $data['label'] ?? '';
	$title = $data['title'] ?? '';
	$text  = $data['text'] ?? '';

	if ( ! $label && ! $title && ! $text ) {
		return;
	}

	$tag     = $opts['tag'] ?? 'h2';
	$classes = trim( 'lead reveal ' . ( ! empty( $opts['split'] ) ? 'lead--split ' : '' ) . ( $opts['class'] ?? '' ) );
	?>
	<header class="<?php echo esc_attr( $classes ); ?>">
		<div class="lead__head">
			<?php if ( $label ) : ?>
				<p class="eyebrow"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<<?php echo tag_escape( $tag ); ?> class="lead__title"><?php echo wp_kses_post( $title ); ?></<?php echo tag_escape( $tag ); ?>>
			<?php endif; ?>
		</div>
		<?php if ( $text ) : ?>
			<p class="lead__text"><?php echo wp_kses_post( $text ); ?></p>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Normalise a relationship/post-object field to a list of post ids.
 *
 * @param mixed $field WP_Post objects, ids, or a mix.
 * @return int[]
 */
function severus_ids( $field ): array {
	$ids = array();

	foreach ( (array) $field as $item ) {
		$id = is_object( $item ) ? (int) $item->ID : (int) $item;

		if ( $id && 'publish' === get_post_status( $id ) ) {
			$ids[] = $id;
		}
	}

	return $ids;
}

/**
 * The closing call to action most inner pages end on.
 *
 * @return array<string,mixed>
 */
function severus_get_started(): array {
	return array(
		'background' => get_theme_file_uri( 'assets/media/img-bg-make.webp' ),
		'title'  => __( 'Get everything you need to start development', 'severus-noir' ),
		'text'   => __( 'Make informed decisions to optimize your products, services, and projects in the context of your market, and add value by looking at the wider business picture.', 'severus-noir' ),
		'button' => array(
			'title' => __( 'Get Started', 'severus-noir' ),
			'url'   => severus_contact_url(),
		),
	);
}

/**
 * One case study card: the picture on top, and a dark panel below it with the
 * title, a three-line summary and the round arrow.
 */
function severus_work_card( int $id ): void {
	$fields  = get_field( 'cases_fields', $id );
	$summary = is_array( $fields ) ? ( $fields['short_description'] ?? '' ) : '';
	$summary = wp_strip_all_tags( $summary ?: get_the_excerpt( $id ) );
	?>
	<a class="work" href="<?php echo esc_url( get_permalink( $id ) ); ?>" data-snake-arrow>
		<?php if ( has_post_thumbnail( $id ) ) : ?>
			<span class="work__art">
				<?php echo get_the_post_thumbnail( $id, 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
			</span>
		<?php else : ?>
			<span class="work__art work__art--blank"></span>
		<?php endif; ?>

		<span class="work__panel">
			<h3 class="work__title"><?php echo esc_html( get_the_title( $id ) ); ?></h3>
			<?php if ( $summary ) : ?>
				<p class="work__text"><?php echo esc_html( $summary ); ?></p>
			<?php endif; ?>
			<span class="work__go" aria-hidden="true"><?php severus_arrow(); ?></span>
		</span>
	</a>
	<?php
}

/**
 * The 3D orbit mark. Mounted by components/orbit/orbit.js when it nears the
 * viewport; the still preview shows until then, and stays when motion is
 * reduced or WebGL fails.
 *
 * @param string $class  Positioning class(es) for the host.
 * @param string $scroll 'section' turns it with its own section,
 *                       'page' with the whole page.
 */
function severus_orbit( string $class = '', string $scroll = 'section' ): void {
	?>
	<div
		class="<?php echo esc_attr( trim( 'orbit ' . $class ) ); ?>"
		aria-hidden="true"
		data-orbit="<?php echo esc_attr( $scroll ); ?>"
		data-model="<?php echo esc_url( get_theme_file_uri( 'assets/media/orbit.glb' ) ); ?>"
	>
		<img class="orbit__still" src="<?php echo esc_url( get_theme_file_uri( 'assets/media/orbit-preview.webp' ) ); ?>" alt="" width="1200" height="1200" loading="lazy">
	</div>
	<?php
}

/**
 * The services to suggest beside one: the rest of its family. On a child's
 * page that is its siblings, on a top-level service's page its children.
 * Ordered by menu order, then title.
 *
 * The tree is two levels deep, so everything under a parent is a leaf and
 * the family never includes a parent itself.
 *
 * @return int[]
 */
function severus_service_siblings( int $id ): array {
	$parent = (int) wp_get_post_parent_id( $id );

	return get_posts(
		array(
			'post_type'    => 'service',
			'numberposts'  => -1,
			'fields'       => 'ids',
			'post_parent'  => $parent ? $parent : $id,
			'post__not_in' => array( $id ),
			'orderby'      => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
}
