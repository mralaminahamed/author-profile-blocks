<?php
/**
 * Author Position Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_class_attr = 'apbl-author-position';

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
?>
<div class="<?php echo esc_attr( $apbl_class_attr ); ?>"<?php echo ! empty( $apbl_style ) ? ' style="' . esc_attr( $apbl_style ) . '"' : ''; ?>>
	<?php echo esc_html( $author['position'] ?? '' ); ?>
</div>