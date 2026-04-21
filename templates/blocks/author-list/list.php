<?php
/**
 * Author List Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_List_Block $block_instance Block instance
 * @var string $wrapper_attributes Block wrapper attributes
 */

use AuthorProfileBlocks\Blocks\Author_List_Block;

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
	// List classes based on style and options.
	$apbl_list_classes   = array( 'apbl-author-list' );
	$apbl_list_classes[] = 'apbl-display-' . ( $attributes['displayStyle'] ?? 'compact' );
	$apbl_list_classes[] = 'apbl-text-align-' . ( $attributes['textAlign'] ?? 'left' );

	// Add layout preset class.
	if ( ! empty( $attributes['layoutPreset'] ) ) {
		$apbl_list_classes[] = esc_attr( $attributes['layoutPreset'] );
	}

	// Add animation classes.
	if ( ! empty( $attributes['animationType'] ) && $attributes['animationType'] !== 'none' ) {
		$apbl_list_classes[] = 'has-' . esc_attr( $attributes['animationType'] ) . '-animation';
	}

	// Add hover effect class.
	if ( ! empty( $attributes['hoverEffect'] ) && $attributes['hoverEffect'] !== 'none' ) {
		$apbl_list_classes[] = 'has-' . esc_attr( $attributes['hoverEffect'] ) . '-hover';
	}

	// Add Google Font class.
	if ( ! empty( $attributes['googleFont'] ) ) {
		$apbl_list_classes[] = 'has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
	}

	if ( ! empty( $attributes['enableDividers'] ) ) {
		$apbl_list_classes[] = 'has-dividers';
	}

	// Add section spacing custom property.
	$apbl_list_style = '';
	if ( isset( $attributes['sectionSpacing'] ) ) {
		$apbl_list_style .= '--author-list-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
	}

	// Add custom CSS variables.
	if ( ! empty( $attributes['customVar1'] ) ) {
		$apbl_list_style .= '--author-list-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
	}
	if ( ! empty( $attributes['customVar2'] ) ) {
		$apbl_list_style .= '--author-list-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
	}

	$apbl_list_tag = $attributes['listStyle'] ?? 'ul';
	?>
	<div class="<?php echo esc_attr( implode( ' ', $apbl_list_classes ) ); ?>"<?php echo ! empty( $apbl_list_style ) ? ' style="' . esc_attr( $apbl_list_style ) . '"' : ''; ?>>
		<<?php echo esc_attr( $apbl_list_tag ); ?> class="apbl-author-list-items">
			<?php foreach ( $authors as $apbl_author ) : ?>
				<?php echo wp_kses_post( $block_instance->render_author_item( $apbl_author, $attributes ) ); ?>
			<?php endforeach; ?>
		</<?php echo esc_attr( $apbl_list_tag ); ?>>
	</div>
</div>
