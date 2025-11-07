<?php
/**
 * Author Grid Compact Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var Author_Grid_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="apb-author-compact">
	<?php if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) : ?>
		<?php echo $block_instance->render_author_image( $author ); ?>
	<?php endif; ?>

	<div class="apb-author-info">
		<?php if ( ! empty( $author['title'] ) ) : ?>
			<?php echo $block_instance->render_author_name( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) : ?>
			<?php echo $block_instance->render_author_position( $author ); ?>
		<?php endif; ?>
	</div>
</div>