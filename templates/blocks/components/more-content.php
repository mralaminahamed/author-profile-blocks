<?php
/**
 * More Content Component Template
 *
 * @package AuthorProfileBlocks
 * @var string $content More content HTML
 * @var array $author Author data with style attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$styles = array();

// Add border color if specified
if ( ! empty( $author['moreContentBorderColor'] ) ) {
	$styles[] = 'border-top-color: ' . esc_attr( $author['moreContentBorderColor'] );
}

// Add top padding if specified
if ( ! empty( $author['moreContentPadding'] ) ) {
	$styles[] = 'padding-top: ' . esc_attr( $author['moreContentPadding'] ) . 'px';
}

$style_html = ! empty( $styles ) ? ' style="' . implode( '; ', $styles ) . '"' : '';
?>
<div class="apbl-author-more-content"<?php echo $style_html; ?>>
	<?php echo wp_kses_post( $content ); ?>
</div>