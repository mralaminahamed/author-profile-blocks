<?php
/**
 * Author List Item Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $item_class CSS classes for the item
 * @var string $style_attribute Inline styles for the item
 * @var string $display_style Display style (compact, detailed)
 * @var string $author_content Rendered author content HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="<?php echo esc_attr( $item_class ); ?>"<?php echo $style_attribute; ?>>
	<div class="apbl-author-list-item-content">
		<?php echo $author_content; ?>
	</div>
</li>