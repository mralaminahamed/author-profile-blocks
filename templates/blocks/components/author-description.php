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

$class_attr = 'apbl-author-description';

$style = '';
if ( isset( $author['descriptionSize'] ) ) {
	$style .= 'font-size: ' . (int) $author['descriptionSize'] . 'px;';
}
if ( isset( $author['descriptionLineHeight'] ) ) {
	$style .= 'line-height: ' . esc_attr( $author['descriptionLineHeight'] ) . ';';
}
if ( ! empty( $author['descriptionColor'] ) ) {
	$style .= 'color: ' . esc_attr( $author['descriptionColor'] ) . ';';
}
if ( ! empty( $author['descriptionStyle'] ) ) {
	$style .= 'font-style: ' . esc_attr( $author['descriptionStyle'] ) . ';';
}
if ( ! empty( $author['descriptionAlignment'] ) ) {
	$style .= 'text-align: ' . esc_attr( $author['descriptionAlignment'] ) . ';';
}
if ( isset( $author['descriptionMargin'] ) ) {
	$style .= 'margin: ' . (int) $author['descriptionMargin'] . 'px;';
}
?>
<div class="<?php echo esc_attr( $class_attr ); ?>"<?php echo ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
	<?php echo wp_kses_post( $author['description'] ); ?>
</div>