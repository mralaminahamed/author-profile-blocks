<?php
/**
 * Show Email Field Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options = get_option( 'author_profile_blocks_settings', array() );
$show_email = isset( $options['show_email'] ) ? $options['show_email'] : 0;
?>
<label>
	<input type="checkbox" name="author_profile_blocks_settings[show_email]" value="1" <?php checked( $show_email, 1 ); ?> />
	<?php esc_html_e( 'Display email addresses in author profiles', 'author-profile-blocks' ); ?>
</label>
<p class="description"><?php esc_html_e( 'Warning: Displaying email addresses publicly may increase spam.', 'author-profile-blocks' ); ?></p>