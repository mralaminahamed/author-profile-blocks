<?php
/**
 * Common Author Profile Content Template
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
 * @var string $layout_type Layout type (image-content, content-image, image-top, content-top)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Build container classes with advanced features
$apbl_container_class = 'apbl-author-profile-content';

// Add layout preset class
if ( ! empty( $attributes['layoutPreset'] ) ) {
	$apbl_container_class .= ' ' . esc_attr( $attributes['layoutPreset'] );
}

// Add animation classes
if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
	$apbl_container_class .= ' has-' . esc_attr( $attributes['animationType'] ) . '-animation';
}

// Add hover effect class
if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
	$apbl_container_class .= ' has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
}

// Add Google Font class
if ( ! empty( $attributes['googleFont'] ) ) {
	$apbl_container_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

// Build inline styles with CSS custom properties
$apbl_container_style = '';

// Add section spacing custom property
if ( isset( $attributes['sectionSpacing'] ) ) {
	$apbl_container_style .= '--author-profile-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
}

// Add custom CSS variables
if ( ! empty( $attributes['customVar1'] ) ) {
	$apbl_container_style .= '--author-profile-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
}
if ( ! empty( $attributes['customVar2'] ) ) {
	$apbl_container_style .= '--author-profile-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
}
?>
<div class="<?php echo esc_attr( $apbl_container_class ); ?>"<?php echo ! empty( $apbl_container_style ) ? ' style="' . esc_attr( $apbl_container_style ) . '"' : ''; ?>>
	<?php
	switch ( $layout_type ) {
		case 'content-image':
			author_profile_blocks()->get_template(
				'blocks/author-profile/content-image.php',
				array(
					'author'             => $author,
					'attributes'         => $attributes,
					'author_image'       => $author_image,
					'author_name'        => $author_name,
					'author_position'    => $author_position,
					'author_email'       => $author_email,
					'registered_date'    => $registered_date,
					'author_description' => $author_description,
					'social_links'       => $social_links,
				)
			);
			break;

		case 'image-top':
			author_profile_blocks()->get_template(
				'blocks/author-profile/image-top.php',
				array(
					'author'             => $author,
					'attributes'         => $attributes,
					'author_image'       => $author_image,
					'author_name'        => $author_name,
					'author_position'    => $author_position,
					'author_email'       => $author_email,
					'registered_date'    => $registered_date,
					'author_description' => $author_description,
					'social_links'       => $social_links,
				)
			);
			break;

		case 'content-top':
			author_profile_blocks()->get_template(
				'blocks/author-profile/content-top.php',
				array(
					'author'             => $author,
					'attributes'         => $attributes,
					'author_image'       => $author_image,
					'author_name'        => $author_name,
					'author_position'    => $author_position,
					'author_email'       => $author_email,
					'registered_date'    => $registered_date,
					'author_description' => $author_description,
					'social_links'       => $social_links,
				)
			);
			break;

		case 'image-content':
		default:
			author_profile_blocks()->get_template(
				'blocks/author-profile/image-content.php',
				array(
					'author'             => $author,
					'attributes'         => $attributes,
					'author_image'       => $author_image,
					'author_name'        => $author_name,
					'author_position'    => $author_position,
					'author_email'       => $author_email,
					'registered_date'    => $registered_date,
					'author_description' => $author_description,
					'social_links'       => $social_links,
				)
			);
			break;
	}
	?>
</div>
