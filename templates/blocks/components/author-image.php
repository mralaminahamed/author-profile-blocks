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

$classes = 'apbl-author-image';
if ( ! empty( $additional_class ) ) {
	$classes .= ' ' . esc_attr( $additional_class );
}

if ( ! empty( $author['avatarAlignment'] ) ) {
	$classes .= ' apbl-author-image-align-' . esc_attr( $author['avatarAlignment'] );
}

$image_classes = array();

// Add avatar shape class if available from attributes
if ( ! empty( $author['avatarShape'] ) ) {
	$image_classes[] = 'avatar-shape-' . esc_attr( $author['avatarShape'] );
}

// Build custom CSS for the avatar
$avatar_styles = array();

// Size
if ( ! empty( $author['avatarSize'] ) ) {
	$avatar_styles[] = 'width: ' . esc_attr( $author['avatarSize'] ) . 'px';
	$avatar_styles[] = 'height: ' . esc_attr( $author['avatarSize'] ) . 'px';
}

// Border
if ( ! empty( $author['avatarBorderWidth'] ) && $author['avatarBorderWidth'] > 0 ) {
	$avatar_styles[] = 'border-width: ' . esc_attr( $author['avatarBorderWidth'] ) . 'px';
	$avatar_styles[] = 'border-style: solid';

	if ( ! empty( $author['avatarBorderColor'] ) ) {
		$avatar_styles[] = 'border-color: ' . esc_attr( $author['avatarBorderColor'] );
	}
}

// Custom border radius for custom shape
if ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'custom' && ! empty( $author['avatarBorderRadius'] ) ) {
	$avatar_styles[] = 'border-radius: ' . esc_attr( $author['avatarBorderRadius'] ) . 'px';
} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'circle' ) {
	$avatar_styles[] = 'border-radius: 50%';
} elseif ( ! empty( $author['avatarShape'] ) && $author['avatarShape'] === 'rounded' ) {
	$avatar_styles[] = 'border-radius: 8px';
}

// Margin
if ( ! empty( $author['avatarMargin'] ) ) {
	$avatar_styles[] = 'margin-bottom: ' . esc_attr( $author['avatarMargin'] ) . 'px';
}

// Object fit to ensure proper sizing
$avatar_styles[] = 'object-fit: cover';

$image_attr = array(
	'class'   => ! empty( $image_classes ) ? implode( ' ', $image_classes ) : '',
	'alt'     => esc_attr( $author['title'] ),
	'loading' => 'lazy',
	'style'   => implode( '; ', $avatar_styles ),
);
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo wp_get_attachment_image( $author['image'], 'full', false, $image_attr ); ?>
</div>