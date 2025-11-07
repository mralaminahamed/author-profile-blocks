<?php
/**
 * Author Grid Item Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var Author_Grid_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get item styles.
$item_styles     = $block_instance->get_item_styles( $attributes );
$style_attribute = '';

if ( ! empty( $item_styles ) ) {
	$style_attribute = ' style="' . $block_instance->get_styles_string( $item_styles ) . '"';
}

// Item classes based on layout and options.
$item_classes = array( 'apb-author-grid-item' );

// Add layout class.
$layout         = $attributes['layout'] ?? 'card';
$item_classes[] = 'is-layout-' . $layout;

// Add shadow class if enabled.
if ( ! empty( $attributes['enableShadow'] ) ) {
	$item_classes[] = 'has-shadow';
}

// Add border class if enabled.
if ( ! empty( $attributes['enableBorder'] ) ) {
	$item_classes[] = 'has-border';
}

// Add rounded class if enabled.
if ( ! empty( $attributes['enableRounded'] ) ) {
	$item_classes[] = 'is-rounded';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>"<?php echo $style_attribute; ?>>
	<?php
	// Use the appropriate layout template based on the selected layout.
	switch ( $layout ) {
		case 'compact':
			wc_get_template(
				'blocks/author-grid/layouts/compact.php',
				array(
					'author'         => $author,
					'attributes'     => $attributes,
					'block_instance' => $block_instance,
				),
				'',
				plugin_dir_path( __FILE__ ) . '../'
			);
			break;

		case 'centered':
			wc_get_template(
				'blocks/author-grid/layouts/centered.php',
				array(
					'author'         => $author,
					'attributes'     => $attributes,
					'block_instance' => $block_instance,
				),
				'',
				plugin_dir_path( __FILE__ ) . '../'
			);
			break;

		case 'card':
		default:
			wc_get_template(
				'blocks/author-grid/layouts/card.php',
				array(
					'author'         => $author,
					'attributes'     => $attributes,
					'block_instance' => $block_instance,
				),
				'',
				plugin_dir_path( __FILE__ ) . '../'
			);
			break;
	}
	?>
</div>