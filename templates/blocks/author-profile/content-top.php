<?php
/**
 * Author Profile Block - Content Top Layout Template
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

// Build container classes with advanced features
$container_class = 'apbl-author-profile-content';

// Add layout preset class
if ( ! empty( $attributes['layoutPreset'] ) ) {
	$container_class .= ' ' . esc_attr( $attributes['layoutPreset'] );
}

// Add animation classes
if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
	$container_class .= ' has-' . esc_attr( $attributes['animationType'] ) . '-animation';
}

// Add hover effect class
if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
	$container_class .= ' has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
}

// Add Google Font class
if ( ! empty( $attributes['googleFont'] ) ) {
	$container_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

// Build inline styles with CSS custom properties
$container_style = '';

// Add section spacing custom property
if ( isset( $attributes['sectionSpacing'] ) ) {
	$container_style .= '--author-profile-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
}

// Add custom CSS variables
if ( ! empty( $attributes['customVar1'] ) ) {
	$container_style .= '--author-profile-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
}
if ( ! empty( $attributes['customVar2'] ) ) {
	$container_style .= '--author-profile-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
}
?>
<div class="<?php echo esc_attr( $container_class ); ?>"<?php echo ! empty( $container_style ) ? ' style="' . esc_attr( $container_style ) . '"' : ''; ?>>
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

	<?php if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) : ?>
		<?php echo $author_image; ?>
	<?php endif; ?>
</div>