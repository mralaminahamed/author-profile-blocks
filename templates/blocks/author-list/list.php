<?php
/**
 * Author List Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_List_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// List classes based on style and options.
$list_classes   = array( 'apbl-authors-list-preview' );
$list_classes[] = 'apbl-display-' . ( $attributes['displayStyle'] ?? 'compact' );
$list_classes[] = 'apbl-text-align-' . ( $attributes['textAlign'] ?? 'left' );

// Add layout preset class.
if ( ! empty( $attributes['layoutPreset'] ) ) {
	$list_classes[] = esc_attr( $attributes['layoutPreset'] );
}

// Add animation classes.
if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
	$list_classes[] = 'has-' . esc_attr( $attributes['animationType'] ) . '-animation';
}

// Add hover effect class.
if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
	$list_classes[] = 'has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
}

// Add Google Font class.
if ( ! empty( $attributes['googleFont'] ) ) {
	$list_classes[] = 'has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

if ( ! empty( $attributes['enableDividers'] ) ) {
	$list_classes[] = 'apbl-has-dividers';
}

// Add section spacing custom property.
$list_style = '';
if ( isset( $attributes['sectionSpacing'] ) ) {
	$list_style .= '--author-list-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
}

// Add custom CSS variables.
if ( ! empty( $attributes['customVar1'] ) ) {
	$list_style .= '--author-list-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
}
if ( ! empty( $attributes['customVar2'] ) ) {
	$list_style .= '--author-list-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
}

$list_tag = $attributes['listStyle'] ?? 'ul';
?>
<div class="<?php echo esc_attr( implode( ' ', $list_classes ) ); ?>"<?php echo ! empty( $list_style ) ? ' style="' . esc_attr( $list_style ) . '"' : ''; ?>>
	<<?php echo $list_tag; ?> class="apbl-authors-list">
		<?php foreach ( $authors as $author ) : ?>
			<?php echo $block_instance->render_author_item( $author, $attributes ); ?>
		<?php endforeach; ?>
	</<?php echo $list_tag; ?>>
</div>