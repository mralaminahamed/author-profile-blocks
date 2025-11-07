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

$class_attr = 'apbl-author-email';

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

$link_style = '';
if ( ! empty( $author['emailLinkColor'] ) ) {
	$link_style .= 'color: ' . esc_attr( $author['emailLinkColor'] ) . ';';
}
?>
<div class="<?php echo esc_attr( $class_attr ); ?>"<?php echo ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
	<a href="<?php echo esc_url( 'mailto:' . $author['email'] ); ?>"<?php echo ! empty( $link_style ) ? ' style="' . esc_attr( $link_style ) . '"' : ''; ?><?php echo ! empty( $author['emailHoverColor'] ) ? ' onmouseover="this.style.color=\'' . esc_attr( $author['emailHoverColor'] ) . '\'" onmouseout="this.style.color=\'' . esc_attr( $author['emailLinkColor'] ?: '' ) . '\'"' : ''; ?>>
		<?php echo esc_html( $author['email'] ); ?>
	</a>
</div>