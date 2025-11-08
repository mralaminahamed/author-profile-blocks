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

$apbl_styles = array();

// Add border color if specified
if ( ! empty( $author['moreContentBorderColor'] ) ) {
	$apbl_styles[] = 'border-top-color: ' . esc_attr( $author['moreContentBorderColor'] );
}

// Add top padding if specified
if ( ! empty( $author['moreContentPadding'] ) ) {
	$apbl_styles[] = 'padding-top: ' . esc_attr( $author['moreContentPadding'] ) . 'px';
}

$apbl_style_html = ! empty( $apbl_styles ) ? ' style="' . implode( '; ', $apbl_styles ) . '"' : '';
?>
<div class="apbl-author-more-content"<?php echo esc_attr( $apbl_style_html ); ?>>
	<?php echo wp_kses_post( $content ); ?>
</div>