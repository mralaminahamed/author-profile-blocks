<?php
/**
 * Author Email Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $author['email'] ) ) {
	return;
}

$apbl_class_attr = 'apbl-author-email';

$apbl_style = '';
if ( isset( $author['metaSize'] ) ) {
	$apbl_style .= 'font-size: ' . (int) $author['metaSize'] . 'px;';
}
if ( ! empty( $author['metaColor'] ) ) {
	$apbl_style .= 'color: ' . esc_attr( $author['metaColor'] ) . ';';
}
if ( ! empty( $author['metaStyle'] ) ) {
	$apbl_style .= 'font-style: ' . esc_attr( $author['metaStyle'] ) . ';';
}
if ( isset( $author['metaBold'] ) && $author['metaBold'] ) {
	$apbl_style .= 'font-weight: bold;';
}
if ( ! empty( $author['metaAlignment'] ) ) {
	$apbl_style .= 'text-align: ' . esc_attr( $author['metaAlignment'] ) . ';';
}
if ( isset( $author['metaMargin'] ) ) {
	$apbl_style .= 'margin: ' . (int) $author['metaMargin'] . 'px;';
}

$apbl_link_style = '';
if ( ! empty( $author['emailLinkColor'] ) ) {
	$apbl_link_style .= 'color: ' . esc_attr( $author['emailLinkColor'] ) . ';';
}

// Add hover color as a CSS custom property for safer styling
$apbl_hover_style = '';
if ( ! empty( $author['emailHoverColor'] ) ) {
	$apbl_hover_style .= '--apbl-email-hover-color: ' . esc_attr( $author['emailHoverColor'] ) . ';';
}
?>
<div class="<?php echo esc_attr( $apbl_class_attr ); ?>"<?php echo ! empty( $apbl_style ) ? ' style="' . esc_attr( $apbl_style ) . '"' : ''; ?><?php echo ! empty( $apbl_hover_style ) ? ' style="' . esc_attr( $apbl_hover_style ) . '"' : ''; ?>>
	<a href="<?php echo esc_url( 'mailto:' . $author['email'] ); ?>"<?php echo ! empty( $apbl_link_style ) ? ' style="' . esc_attr( $apbl_link_style ) . '"' : ''; ?> class="apbl-email-link">
		<?php echo esc_html( $author['email'] ); ?>
	</a>
</div>