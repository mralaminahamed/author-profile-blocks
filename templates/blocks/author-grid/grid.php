<?php
/**
 * Author Grid Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_Grid_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Create grid container with column class.
$grid_class = 'apb-author-grid';
if ( isset( $attributes['columns'] ) ) {
	$grid_class .= ' apb-columns-' . (int) $attributes['columns'];
}

// Add item spacing as inline style.
$grid_style = '';
if ( isset( $attributes['itemSpacing'] ) ) {
	$grid_style = 'gap: ' . (int) $attributes['itemSpacing'] . 'px;';
}
?>
<div class="<?php echo esc_attr( $grid_class ); ?>" style="<?php echo esc_attr( $grid_style ); ?>">
	<?php foreach ( $authors as $author ) : ?>
		<?php echo $block_instance->render_author_item( $author, $attributes ); ?>
	<?php endforeach; ?>
</div>