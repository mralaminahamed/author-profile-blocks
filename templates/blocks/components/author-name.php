<?php
/**
 * Author Name Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_class_attr = 'apbl-author-name';

$apbl_style = '';
if ( isset( $author['nameSize'] ) ) {
	$apbl_style .= 'font-size: ' . (int) $author['nameSize'] . 'px;';
}
if ( ! empty( $author['nameWeight'] ) ) {
	$apbl_style .= 'font-weight: ' . esc_attr( $author['nameWeight'] ) . ';';
}
if ( ! empty( $author['nameColor'] ) ) {
	$apbl_style .= 'color: ' . esc_attr( $author['nameColor'] ) . ';';
}
if ( ! empty( $author['nameTransform'] ) ) {
	$apbl_style .= 'text-transform: ' . esc_attr( $author['nameTransform'] ) . ';';
}
if ( ! empty( $author['nameAlignment'] ) ) {
	$apbl_style .= 'text-align: ' . esc_attr( $author['nameAlignment'] ) . ';';
}
if ( isset( $author['nameMargin'] ) ) {
	$apbl_style .= 'margin: ' . (int) $author['nameMargin'] . 'px;';
}

$apbl_name = $author['title'] ?? $author['name'] ?? $author['display_name'] ?? '';
$apbl_link = ! empty( $author['url'] ) ? $author['url'] : '';

if ( ! empty( $apbl_link ) ) {
	?>
	<h3 class="<?php echo esc_attr( $apbl_class_attr ); ?>"<?php echo ! empty( $apbl_style ) ? ' style="' . esc_attr( $apbl_style ) . '"' : ''; ?>>
		<a href="<?php echo esc_url( $apbl_link ); ?>"<?php echo ! empty( $author['linkTarget'] ) ? ' target="' . esc_attr( $author['linkTarget'] ) . '"' : ''; ?><?php echo ! empty( $author['linkRel'] ) ? ' rel="' . esc_attr( $author['linkRel'] ) . '"' : ''; ?>>
			<?php echo esc_html( $apbl_name ); ?>
		</a>
	</h3>
	<?php
} else {
	?>
	<h3 class="<?php echo esc_attr( $apbl_class_attr ); ?>"<?php echo ! empty( $apbl_style ) ? ' style="' . esc_attr( $apbl_style ) . '"' : ''; ?>>
		<?php echo esc_html( $apbl_name ); ?>
	</h3>
	<?php
}