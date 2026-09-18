<?php
/**
 * Template Name: Styleguide
 * @package Severus_Noir
 */
defined( 'ABSPATH' ) || exit;
add_filter( 'wp_robots', function ( $robots ) { $robots['noindex'] = true; return $robots; } );
get_header();
$sections = array( 'palette' => 'Color palette', 'type' => 'Typography', 'geometry' => 'Spacing & shape', 'actions' => 'Buttons & links', 'components' => 'Components', 'forms' => 'Form fields' );
?>
<div class="sg shell">
<header class="sg__hero">
<p class="sg__eyebrow">Severus Noir / Design reference</p>
<h1>Every detail.<br><em>One language.</em></h1>
<p class="sg__intro">The foundations of Severus Noir. Explore the colors, typography and components that shape the experience.</p>
<a class="link" href="#palette" data-snake-arrow>Explore the styleguide <?php severus_arrow(); ?></a>
</header>
<div class="sg__layout">
<nav class="sg__nav" aria-label="Styleguide sections"><p class="sg__eyebrow">Styleguide</p>
<?php foreach ( $sections as $id => $label ) : ?><a href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a><?php endforeach; ?>
</nav>
<div class="sg__content">
<section class="sg__section rule" id="palette"><p class="sg__eyebrow">01 / Foundations</p><h2>Color palette</h2><p>Obsidian surfaces, emerald accents and silver highlights. Select a swatch to copy its CSS variable.</p>
<div class="sg__swatches">
<?php foreach ( array( 'void', 'pit', 'abyss', 'deep', 'slate', 'hair', 'jade', 'jade-deep', 'jade-dim', 'facet', 'pewter', 'ice', 'bone', 'ash', 'dust', 'glyph-mid', 'glyph-well' ) as $token ) : ?>
<button type="button" class="sg__swatch" data-sg-copy="var(--<?php echo esc_attr( $token ); ?>)" aria-label="Copy var(--<?php echo esc_attr( $token ); ?>)"><span class="sg__paint" style="background:var(--<?php echo esc_attr( $token ); ?>)"></span><code>--<?php echo esc_html( $token ); ?></code><small data-sg-value="--<?php echo esc_attr( $token ); ?>"></small></button>
<?php endforeach; ?>
</div><p class="sg__status" role="status" aria-live="polite" data-sg-status></p></section>
<section class="sg__section rule" id="type"><p class="sg__eyebrow">02 / Typography</p><h2>A voice with presence.</h2><p>The live font stack and fluid scale used throughout the theme.</p>
<div class="sg__sample"><code>--font-display</code><p class="sg__font" data-sg-value="--font-display"></p><div class="lead"><h3 class="lead__title">Clarity creates <em>momentum.</em></h3><p class="lead__text">A section introduction with <span>emerald emphasis</span>, <span class="mark--cool">silver emphasis</span> and <b>medium weight</b>.</p></div></div>
<div class="sg__sample"><code>--font</code><p data-sg-value="--font"></p><p class="sg__alphabet">Aa Bb Cc Dd Ee Ff Gg<br>0123456789 &amp; @ ! ?</p><p>Body copy makes complex ideas easy to understand. Keep paragraphs focused, readable and comfortably spaced.</p></div>
</section>
<section class="sg__section rule" id="geometry"><p class="sg__eyebrow">03 / Geometry</p><h2>Room to breathe.</h2><div class="sg__shapes">
<?php foreach ( array( 'r-xs', 'r-sm', 'r-md', 'r-lg', 'r-pill' ) as $token ) : ?><div><div class="sg__shape edge" style="border-radius:var(--<?php echo esc_attr( $token ); ?>)"></div><code>--<?php echo esc_html( $token ); ?></code><p data-sg-value="--<?php echo esc_attr( $token ); ?>"></p></div><?php endforeach; ?>
</div><dl class="sg__tokens"><?php foreach ( array( 'gut', 'shell', 'shell-narrow', 'bay', 'head', 'edge', 'ease', 'ease-out' ) as $token ) : ?><div><dt><code>--<?php echo esc_html( $token ); ?></code></dt><dd data-sg-value="--<?php echo esc_attr( $token ); ?>"></dd></div><?php endforeach; ?></dl></section>
<section class="sg__section rule" id="actions"><p class="sg__eyebrow">04 / Actions</p><h2>An invitation to act.</h2><p>Hover or focus to explore the gradient and animated arrow.</p><div class="sg__actions"><a class="btn btn--solid" href="#components" data-snake-arrow>Primary action <?php severus_arrow(); ?></a><a class="btn btn--quiet" href="#components" data-snake-arrow>Quiet action <?php severus_arrow(); ?></a><a class="link" href="#forms" data-snake-arrow>Text link <?php severus_arrow(); ?></a></div></section>
<section class="sg__section rule" id="components"><p class="sg__eyebrow">05 / Components</p><h2>Built to work together.</h2><div class="sg__examples"><a class="card" href="#forms"><h3 class="card__title">Strategy &amp; direction</h3><p class="card__text">A service card with the theme’s gradient edge, typography and hover treatment.</p><span class="card__more">Explore the form <i aria-hidden="true"></i></span></a><figure class="quote"><blockquote><p>“Strong design gives every idea the space and clarity it deserves.”</p></blockquote><figcaption>Sample quotation / Card surface</figcaption></figure></div>
<div class="sg__sample"><p class="sg__eyebrow">Accordion / Native details</p><?php foreach ( array( 'What defines the visual language?' => 'Dark layered surfaces, restrained emerald accents and clear, generous typography.', 'How do these examples stay consistent?' => 'They use the same component classes and CSS variables as the rest of the theme.' ) as $question => $answer ) : ?><details class="ask rule" name="styleguide-faq"><summary><?php echo esc_html( $question ); ?><span class="ask__sign" aria-hidden="true"></span></summary><div class="ask__body"><p><?php echo esc_html( $answer ); ?></p></div></details><?php endforeach; ?></div></section>
<section class="sg__section rule" id="forms"><p class="sg__eyebrow">06 / Inputs</p><h2>Start a conversation.</h2><p>Interactive field previews. This demo does not send a message.</p><div class="form sg__form"><div class="field"><input id="sg-name" type="text" placeholder=" " autocomplete="off"><label for="sg-name">Full name</label></div><div class="field"><input id="sg-email" type="email" placeholder=" " value="hello@example.com" autocomplete="off"><label for="sg-email">Email / Filled</label></div><div class="field field--area"><textarea id="sg-message" placeholder=" " rows="3"></textarea><label for="sg-message">Request details</label></div><button type="button" class="btn btn--solid btn--block" data-sg-demo>Preview interaction</button><p data-sg-demo-status role="status"></p></div></section>
</div></div></div>
<?php get_footer(); ?>
