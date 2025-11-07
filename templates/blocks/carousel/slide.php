<?php
/**
 * Carousel Slide Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $item_class CSS classes for the item
 * @var string $style_attribute Inline styles for the item
 * @var string $layout Layout type (compact, centered, card)
 * @var string $author_content Rendered author content HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="apbl-author-carousel-slide">
	<div class="<?php echo esc_attr( $item_class ); ?>"<?php echo $style_attribute; ?>>
		<?php echo $author_content; ?>
	</div>
</div>