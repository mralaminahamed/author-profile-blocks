<?php
/**
 * Minimal Layout Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$author      = $template_vars['author'] ?? array();
$attributes  = $template_vars['attributes'] ?? array();
$block_class = $template_vars['block_class'] ?? '';
$styles      = $template_vars['styles'] ?? '';

// Safely extract author data with fallbacks
$author_name     = $author['title'] ?? $author['name'] ?? $author['display_name'] ?? '';
$author_image    = $author['image'] ?? '';
$author_position = $author['position'] ?? '';
$author_email    = $author['email'] ?? '';
$show_image      = $attributes['showImage'] ?? true;
$show_email      = $attributes['showEmail'] ?? false;
?>

<div class="author-profile-blocks-minimal <?php echo esc_attr( $block_class ); ?>"<?php echo $styles ? ' style="' . esc_attr( $styles ) . '"' : ''; ?>>
	<?php if ( ! empty( $author_image ) && $show_image ) : ?>
		<div class="author-profile-blocks-minimal__image">
			<img src="<?php echo esc_url( $author_image ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" loading="lazy" />
		</div>
	<?php endif; ?>

	<div class="author-profile-blocks-minimal__content">
		<?php if ( ! empty( $author_name ) ) : ?>
			<h3 class="author-profile-blocks-minimal__name"><?php echo esc_html( $author_name ); ?></h3>
		<?php endif; ?>

		<?php if ( ! empty( $author_position ) ) : ?>
			<p class="author-profile-blocks-minimal__position"><?php echo esc_html( $author_position ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $author_email ) && $show_email ) : ?>
			<p class="author-profile-blocks-minimal__email">
				<a href="<?php echo esc_url( 'mailto:' . $author_email ); ?>"><?php echo esc_html( $author_email ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</div>