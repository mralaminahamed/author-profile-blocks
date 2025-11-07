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

$class_attr = 'apbl-author-name';

$style = '';
if ( isset( $author['nameSize'] ) ) {
	$style .= 'font-size: ' . (int) $author['nameSize'] . 'px;';
}
if ( ! empty( $author['nameWeight'] ) ) {
	$style .= 'font-weight: ' . esc_attr( $author['nameWeight'] ) . ';';
}
if ( ! empty( $author['nameColor'] ) ) {
	$style .= 'color: ' . esc_attr( $author['nameColor'] ) . ';';
}
if ( ! empty( $author['nameTransform'] ) ) {
	$style .= 'text-transform: ' . esc_attr( $author['nameTransform'] ) . ';';
}
if ( ! empty( $author['nameAlignment'] ) ) {
	$style .= 'text-align: ' . esc_attr( $author['nameAlignment'] ) . ';';
}
if ( isset( $author['nameMargin'] ) ) {
	$style .= 'margin: ' . (int) $author['nameMargin'] . 'px;';
}

$name = $author['title'] ?? $author['name'] ?? $author['display_name'] ?? '';
$link = ! empty( $author['url'] ) ? $author['url'] : '';

if ( ! empty( $link ) ) {
	?>
	<h3 class="<?php echo esc_attr( $class_attr ); ?>"<?php echo ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
		<a href="<?php echo esc_url( $link ); ?>"<?php echo ! empty( $author['linkTarget'] ) ? ' target="' . esc_attr( $author['linkTarget'] ) . '"' : ''; ?><?php echo ! empty( $author['linkRel'] ) ? ' rel="' . esc_attr( $author['linkRel'] ) . '"' : ''; ?>>
			<?php echo esc_html( $name ); ?>
		</a>
	</h3>
	<?php
} else {
	?>
	<h3 class="<?php echo esc_attr( $class_attr ); ?>"<?php echo ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
		<?php echo esc_html( $name ); ?>
	</h3>
	<?php
}