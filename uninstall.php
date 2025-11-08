<?php
/**
 * Author Profile Blocks Uninstall
 *
 * @package AuthorProfileBlocks
 */

// Prevent direct access.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check if uninstall is requested.
if ( ! defined( 'AUTHOR_PROFILE_BLOCKS_UNINSTALL' ) || ! AUTHOR_PROFILE_BLOCKS_UNINSTALL ) {
	return;
}

// Delete plugin options.
delete_option( 'author_profile_blocks_settings' );
delete_option( 'author_profile_blocks_activated' );

// Delete transients.
delete_transient( 'author_profile_blocks_temp_data' );

// Clean up any custom database tables if they exist.
// Note: This plugin doesn't create custom tables, but this is where you would add cleanup code.

// Clear any cached data.
wp_cache_flush();

// Note: We don't delete user data or posts created by this plugin
// as they may contain important content. Manual cleanup may be required.
