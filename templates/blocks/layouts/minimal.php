<?php
/**
 * Minimal Layout Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author      = $template_vars['author'] ?? array();
$attributes  = $template_vars['attributes'] ?? array();
$block_class = $template_vars['block_class'] ?? '';
$styles      = $template_vars['styles'] ?? '';
?>

<div class="author-profile-blocks-minimal <?php echo esc_attr( $block_class ); ?>"<?php echo $styles ? ' style="' . esc_attr( $styles ) . '"' : ''; ?>>
	<?php if ( ! empty( $author['image'] ) && ! empty( $attributes['showImage'] ) ) : ?>
		<div class="author-profile-blocks-minimal__image">
			<img src="<?php echo esc_url( $author['image'] ); ?>" alt="<?php echo esc_attr( $author['title'] ); ?>" />
		</div>
	<?php endif; ?>

	<div class="author-profile-blocks-minimal__content">
		<?php if ( ! empty( $author['title'] ) ) : ?>
			<h3 class="author-profile-blocks-minimal__name"><?php echo esc_html( $author['title'] ); ?></h3>
		<?php endif; ?>

		<?php if ( ! empty( $author['position'] ) ) : ?>
			<p class="author-profile-blocks-minimal__position"><?php echo esc_html( $author['position'] ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $author['email'] ) && ! empty( $attributes['showEmail'] ) ) : ?>
			<p class="author-profile-blocks-minimal__email">
				<a href="mailto:<?php echo esc_attr( $author['email'] ); ?>"><?php echo esc_html( $author['email'] ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</div>