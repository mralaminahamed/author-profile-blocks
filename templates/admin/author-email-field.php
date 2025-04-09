<?php
/**
 * Template for author email field in the meta box
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 *
 * @var string $email The author's email address
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpas-meta-field">
	<label for="wpas_author_email"><?php esc_html_e( 'Email Address', 'wp-author-showcase' ); ?>:</label>
	<input type="email" id="wpas_author_email" name="wpas_author_email" value="<?php echo esc_attr( $email ); ?>" class="widefat">
</div> 