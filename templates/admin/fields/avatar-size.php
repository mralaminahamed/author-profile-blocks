<?php
/**
 * Avatar Size Field Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_avatar_size = author_profile_blocks()->settings->get_avatar_size();
?>
<input type="number" name="author_profile_blocks_settings[avatar_size]" value="<?php echo esc_attr( $apbl_avatar_size ); ?>" min="32" max="512" class="small-text" />
<p class="description"><?php esc_html_e( 'Default avatar size in pixels (32-512).', 'author-profile-blocks' ); ?></p>
