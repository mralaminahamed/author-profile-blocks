<?php
/**
 * Registered Date Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class_attr = 'apbl-author-registered-date';

$style = '';
if ( isset( $author['metaSize'] ) ) {
	$style .= 'font-size: ' . (int) $author['metaSize'] . 'px;';
}
if ( ! empty( $author['metaColor'] ) ) {
	$style .= 'color: ' . esc_attr( $author['metaColor'] ) . ';';
}
if ( ! empty( $author['metaStyle'] ) ) {
	$style .= 'font-style: ' . esc_attr( $author['metaStyle'] ) . ';';
}
if ( isset( $author['metaBold'] ) && $author['metaBold'] ) {
	$style .= 'font-weight: bold;';
}
if ( ! empty( $author['metaAlignment'] ) ) {
	$style .= 'text-align: ' . esc_attr( $author['metaAlignment'] ) . ';';
}
if ( isset( $author['metaMargin'] ) ) {
	$style .= 'margin: ' . (int) $author['metaMargin'] . 'px;';
}

$label = $author['member_since_label'] ?? __( 'Member since', 'author-profile-blocks' );
?>
<div class="<?php echo esc_attr( $class_attr ); ?>"<?php echo ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
	<span class="apbl-registered-date-label"><?php echo esc_html( $label ); ?></span> <span class="apbl-registered-date-value"><?php echo esc_html( $author['registered_date'] ); ?></span>
</div>