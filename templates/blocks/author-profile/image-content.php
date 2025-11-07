<?php
/**
 * Author Profile Block - Image Content Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 * @var string $author_email Rendered author email HTML
 * @var string $registered_date Rendered registration date HTML
 * @var string $author_description Rendered author description HTML
 * @var string $social_links Rendered social links HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="apb-author-profile-content">
	<?php if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) : ?>
		<?php echo $author_image; ?>
	<?php endif; ?>

	<div class="apb-author-info">
		<?php if ( ! empty( $author['title'] ) ) : ?>
			<?php echo $author_name; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) : ?>
			<?php echo $author_position; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) : ?>
			<?php echo $author_email; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) : ?>
			<?php echo $registered_date; ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) : ?>
			<?php echo $author_description; ?>
		<?php endif; ?>

		<?php if ( ! empty( $social_links ) ) : ?>
			<?php echo $social_links; ?>
		<?php endif; ?>
	</div>
</div>