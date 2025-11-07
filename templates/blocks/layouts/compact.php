<?php
/**
 * Author Compact Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 * @var string $author_email Rendered author email HTML
 * @var string $author_description Rendered author description HTML
 * @var string $social_links Rendered social links HTML
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

		<?php if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) : ?>
			<?php echo $author_email; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) : ?>
			<?php echo $author_description; ?>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) : ?>
		<div class="apbl-author-social">
			<?php echo $social_links; ?>
		</div>
	<?php endif; ?>
</div>