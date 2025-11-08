<?php
/**
 * Author Grid Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_Grid_Block $block_instance Block instance
 * @var string $wrapper_attributes Block wrapper attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div
<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $wrapper_attributes is properly escaped
echo $wrapper_attributes;
?>
>
	<?php
	// Create grid container with column class.
	$apbl_grid_class = 'apbl-author-grid';
	if ( isset( $attributes['columns'] ) ) {
		$apbl_grid_class .= ' apbl-columns-' . (int) $attributes['columns'];
	}

	// Add layout preset class.
	if ( ! empty( $attributes['layoutPreset'] ) ) {
		$apbl_grid_class .= ' ' . esc_attr( $attributes['layoutPreset'] );
	}

	// Add animation classes.
	if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
		$apbl_grid_class .= ' has-' . esc_attr( $attributes['animationType'] ) . '-animation';
	}

	// Add hover effect class.
	if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
		$apbl_grid_class .= ' has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
	}

	// Add Google Font class.
	if ( ! empty( $attributes['googleFont'] ) ) {
		$apbl_grid_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
	}

	// Add item spacing as inline style.
	$apbl_grid_style = '';
	if ( isset( $attributes['itemSpacing'] ) ) {
		$apbl_grid_style = 'gap: ' . (int) $attributes['itemSpacing'] . 'px;';
	}

	// Add section spacing custom property.
	if ( isset( $attributes['sectionSpacing'] ) ) {
		$apbl_grid_style .= '--author-grid-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
	}

	// Add custom CSS variables.
	if ( ! empty( $attributes['customVar1'] ) ) {
		$apbl_grid_style .= '--author-grid-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
	}
	if ( ! empty( $attributes['customVar2'] ) ) {
		$apbl_grid_style .= '--author-grid-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
	}
	?>
	<div class="<?php echo esc_attr( $apbl_grid_class ); ?>" style="<?php echo esc_attr( $apbl_grid_style ); ?>">
		<?php foreach ( $authors as $apbl_author ) : ?>
			<?php echo wp_kses_post( $block_instance->render_author_item( $apbl_author, $attributes ) ); ?>
		<?php endforeach; ?>
	</div>
</div>