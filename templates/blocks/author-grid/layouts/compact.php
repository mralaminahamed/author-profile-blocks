<?php
/**
 * Author Grid Compact Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="apbl-author-compact">
	<?php if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) : ?>
		<?php echo $author_image; ?>
	<?php endif; ?>

	<div class="apbl-author-info">
		<?php if ( ! empty( $author['title'] ) ) : ?>
			<?php echo $author_name; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) : ?>
			<?php echo $author_position; ?>
		<?php endif; ?>
	</div>
</div>