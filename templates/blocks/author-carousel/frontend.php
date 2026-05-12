<?php
/**
 * Author Carousel Block Frontend Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_Carousel_Block $block_instance Block instance
 * @var string $wrapper_attributes Block wrapper attributes
 * @var array $carousel_settings Carousel settings for Slick
 */

use AuthorProfileBlocks\Blocks\Author_Carousel_Block;

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
	<div class="apbl-author-carousel" data-settings="<?php echo esc_attr( wp_json_encode( $carousel_settings ) ); ?>"
	<?php
	if ( isset( $attributes['slideSpacing'] ) && $attributes['slideSpacing'] > 0 ) {
		echo ' style="--author-carousel-slide-spacing:' . (int) $attributes['slideSpacing'] . 'px"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- (int) cast is safe
	}
	?>
	>
		<?php foreach ( $authors as $apbl_author ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- render_author_slide() returns properly escaped HTML
			echo $block_instance->render_author_slide( $apbl_author, $attributes );
			?>
		<?php endforeach; ?>
	</div>
</div>
