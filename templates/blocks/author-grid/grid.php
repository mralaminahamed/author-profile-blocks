<?php
/**
 * Author Grid Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_Grid_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Create grid container with column class.
$grid_class = 'apbl-author-grid';
if ( isset( $attributes['columns'] ) ) {
	$grid_class .= ' apbl-columns-' . (int) $attributes['columns'];
}

// Add layout preset class.
if ( ! empty( $attributes['layoutPreset'] ) ) {
	$grid_class .= ' ' . esc_attr( $attributes['layoutPreset'] );
}

// Add animation classes.
if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
	$grid_class .= ' has-' . esc_attr( $attributes['animationType'] ) . '-animation';
}

// Add hover effect class.
if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
	$grid_class .= ' has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
}

// Add Google Font class.
if ( ! empty( $attributes['googleFont'] ) ) {
	$grid_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

// Add item spacing as inline style.
$grid_style = '';
if ( isset( $attributes['itemSpacing'] ) ) {
	$grid_style = 'gap: ' . (int) $attributes['itemSpacing'] . 'px;';
}

// Add section spacing custom property.
if ( isset( $attributes['sectionSpacing'] ) ) {
	$grid_style .= '--author-grid-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
}

// Add custom CSS variables.
if ( ! empty( $attributes['customVar1'] ) ) {
	$grid_style .= '--author-grid-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
}
if ( ! empty( $attributes['customVar2'] ) ) {
	$grid_style .= '--author-grid-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
}
?>
<div class="<?php echo esc_attr( $grid_class ); ?>" style="<?php echo esc_attr( $grid_style ); ?>">
	<?php foreach ( $authors as $author ) : ?>
		<?php echo $block_instance->render_author_item( $author, $attributes ); ?>
	<?php endforeach; ?>
</div>