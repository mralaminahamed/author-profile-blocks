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

$apbl_show_email = author_profile_blocks()->settings->show_email_enabled() ? 1 : 0;
?>
<label>
	<input type="checkbox" name="author_profile_blocks_settings[show_email]" value="1" <?php checked( $apbl_show_email, 1 ); ?> />
	<?php esc_html_e( 'Display email addresses in author profiles', 'author-profile-blocks' ); ?>
</label>
<p class="description"><?php esc_html_e( 'Warning: Displaying email addresses publicly may increase spam.', 'author-profile-blocks' ); ?></p>
