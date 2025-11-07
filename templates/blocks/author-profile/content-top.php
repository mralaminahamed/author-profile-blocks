<?php
/**
 * Author Profile Block - Content Top Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var Author_Profile_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="apb-author-profile-content">
	<div class="apb-author-info">
		<?php if ( ! empty( $author['title'] ) ) : ?>
			<?php echo $block_instance->render_author_name( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) : ?>
			<?php echo $block_instance->render_author_position( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) : ?>
			<?php echo $block_instance->render_author_email( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) : ?>
			<?php echo $block_instance->render_registered_date( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) : ?>
			<?php echo $block_instance->render_author_description( $author ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocialLinks'] ) ) : ?>
			<?php
			$social_links_to_show = $attributes['socialLinksToShow'] ?? array();
			echo $block_instance->render_social_profiles( $author['social'], '', $social_links_to_show );
			?>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) : ?>
		<?php echo $block_instance->render_author_image( $author ); ?>
	<?php endif; ?>
</div>