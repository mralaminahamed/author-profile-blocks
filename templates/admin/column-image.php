<?php
/**
 * Template for the image column in the admin list
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 *
 * @var string $image_url The author's featured image URL
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! empty( $image_url ) ) : ?>
	<div class="author-image-wrapper">
		<img src="<?php echo esc_url( $image_url ); ?>" alt="" class="author-thumbnail" />
	</div>
<?php else : ?>
	<span class="no-image">—</span>
<?php endif; ?>