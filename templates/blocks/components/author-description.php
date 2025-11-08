<?php
/**
 * Author Description Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_class_attr = 'apbl-author-description';

$apbl_style = '';
if ( isset( $author['descriptionSize'] ) ) {
	$apbl_style .= 'font-size: ' . (int) $author['descriptionSize'] . 'px;';
}
if ( isset( $author['descriptionLineHeight'] ) ) {
	$apbl_style .= 'line-height: ' . esc_attr( $author['descriptionLineHeight'] ) . ';';
}
if ( ! empty( $author['descriptionColor'] ) ) {
	$apbl_style .= 'color: ' . esc_attr( $author['descriptionColor'] ) . ';';
}
if ( ! empty( $author['descriptionStyle'] ) ) {
	$apbl_style .= 'font-style: ' . esc_attr( $author['descriptionStyle'] ) . ';';
}
if ( ! empty( $author['descriptionAlignment'] ) ) {
	$apbl_style .= 'text-align: ' . esc_attr( $author['descriptionAlignment'] ) . ';';
}
if ( isset( $author['descriptionMargin'] ) ) {
	$apbl_style .= 'margin: ' . (int) $author['descriptionMargin'] . 'px;';
}
?>
<div class="<?php echo esc_attr( $apbl_class_attr ); ?>"<?php echo ! empty( $apbl_style ) ? ' style="' . esc_attr( $apbl_style ) . '"' : ''; ?>>
	<?php echo wp_kses_post( $author['description'] ?? '' ); ?>
</div>