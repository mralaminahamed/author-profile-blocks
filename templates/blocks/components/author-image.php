<?php
/**
 * Author Image Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $additional_class Additional CSS class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_classes = 'apbl-author-image';
if ( ! empty( $additional_class ) ) {
	$apbl_classes .= ' ' . esc_attr( $additional_class );
}

if ( ! empty( $author['avatarAlignment'] ) ) {
	$apbl_classes .= ' apbl-author-image-align-' . esc_attr( $author['avatarAlignment'] );
}

$apbl_image_classes = array();

// Add avatar shape class if available from attributes
if ( ! empty( $author['avatarShape'] ) ) {
	$apbl_image_classes[] = 'avatar-shape-' . esc_attr( $author['avatarShape'] );
}

// Build custom CSS for the avatar
$apbl_avatar_styles = array();

// Size
if ( ! empty( $author['avatarSize'] ) ) {
	$apbl_avatar_styles[] = 'width: ' . esc_attr( $author['avatarSize'] ) . 'px';
	$apbl_avatar_styles[] = 'height: ' . esc_attr( $author['avatarSize'] ) . 'px';
}

// Border
if ( ! empty( $author['avatarBorderWidth'] ) && $author['avatarBorderWidth'] > 0 ) {
	$apbl_avatar_styles[] = 'border-width: ' . esc_attr( $author['avatarBorderWidth'] ) . 'px';
	$apbl_avatar_styles[] = 'border-style: solid';

	if ( ! empty( $author['avatarBorderColor'] ) ) {
		$apbl_avatar_styles[] = 'border-color: ' . esc_attr( $author['avatarBorderColor'] );
	}
}

// Custom border radius for custom shape
if ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'custom' && ! empty( $author['avatarBorderRadius'] ) ) {
	$apbl_avatar_styles[] = 'border-radius: ' . esc_attr( $author['avatarBorderRadius'] ) . 'px';
} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'circle' ) {
	$apbl_avatar_styles[] = 'border-radius: 50%';
} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'rounded' ) {
	$apbl_avatar_styles[] = 'border-radius: 8px';
}

// Margin
if ( ! empty( $author['avatarMargin'] ) ) {
	$apbl_avatar_styles[] = 'margin-bottom: ' . esc_attr( $author['avatarMargin'] ) . 'px';
}

// Object fit to ensure proper sizing
$apbl_avatar_styles[] = 'object-fit: cover';

// Safely extract author data
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$author_name = $author['title'] ?? $author['name'] ?? $author['display_name'] ?? '';
$image_url   = $author['image'] ?? '';

if ( empty( $image_url ) ) {
	return;
}

$apbl_image_classes = ! empty( $apbl_image_classes ) ? implode( ' ', $apbl_image_classes ) : '';
$apbl_image_style   = implode( '; ', $apbl_avatar_styles );
?>
<div class="<?php echo esc_attr( $apbl_classes ); ?>">
	<img src="<?php echo esc_url( $image_url ); ?>"
		alt="<?php echo esc_attr( $author_name ); ?>"
		class="<?php echo esc_attr( $apbl_image_classes ); ?>"
		style="<?php echo esc_attr( $apbl_image_style ); ?>"
		loading="lazy" />
</div>