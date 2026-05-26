<?php
/**
 * Minimal Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array  $author     Author data (injected via extract() in get_template())
 * @var array  $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Safely extract author data with fallbacks
$apbl_author_name     = $author['title'] ?? $author['name'] ?? $author['display_name'] ?? '';
$apbl_author_url      = $author['url'] ?? '';
$apbl_author_image    = $author['image'] ?? '';
$apbl_author_position = $author['position'] ?? '';
$apbl_author_email    = $author['email'] ?? '';
$apbl_show_image      = $attributes['showImage'] ?? true;
$apbl_show_email      = $attributes['showEmail'] ?? false;
$apbl_show_position   = $attributes['showPosition'] ?? true;
?>

<div class="apbl-author-minimal">
	<?php if ( ! empty( $apbl_author_image ) && $apbl_show_image ) : ?>
		<div class="apbl-author-image">
			<img
				src="<?php echo esc_url( $apbl_author_image ); ?>"
				alt="<?php echo esc_attr( $apbl_author_name ); ?>"
				loading="<?php echo ! isset( $attributes['lazyLoad'] ) || $attributes['lazyLoad'] ? 'lazy' : 'eager'; ?>"
			/>
		</div>
	<?php endif; ?>

	<div class="apbl-author-minimal-info">
		<?php if ( ! empty( $apbl_author_name ) ) : ?>
			<span class="apbl-author-name">
				<?php if ( ! empty( $apbl_author_url ) ) : ?>
					<a href="<?php echo esc_url( $apbl_author_url ); ?>"><?php echo esc_html( $apbl_author_name ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $apbl_author_name ); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>

		<?php if ( ! empty( $apbl_author_position ) && $apbl_show_position ) : ?>
			<span class="apbl-author-position"><?php echo esc_html( $apbl_author_position ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $apbl_author_email ) && $apbl_show_email ) : ?>
			<a class="apbl-author-email" href="<?php echo esc_url( 'mailto:' . $apbl_author_email ); ?>">
				<?php echo esc_html( $apbl_author_email ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
