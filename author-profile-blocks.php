<?php
/**
 * Plugin Name:       Author Profile Blocks
 * Plugin URI:        https://github.com/mralaminahamed/author-profile-blocks
 * Description:       A collection of powerful Gutenberg blocks for showcasing author profiles and team members using WordPress users.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Tested up to:      6.7
 * Requires PHP:      7.4
 * Author:            Al Amin Ahamed
 * Author URI:        https://github.com/mralaminahamed
 * License:           GPL-3.0-only
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       author-profile-blocks
 * Domain Path:       /languages
 *
 * @package AuthorProfileBlocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'APBL_VERSION', '1.0.0' );
define( 'APBL_PLUGIN_FILE', __FILE__ );
define( 'APBL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'APBL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'APBL_PLUGIN_DIR', __DIR__ );

// Load Composer autoloader for PSR-4 classes
if ( ! file_exists( APBL_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	return;
}

require_once APBL_PLUGIN_DIR . 'vendor/autoload.php';

/**
 * Get main plugin instance
 *
 * @since 1.0.0
 * @return Author_Profile_Blocks Plugin instance.
 */
function author_profile_blocks(): Author_Profile_Blocks {
	return Author_Profile_Blocks::get_instance();
}

// Initialize plugin
author_profile_blocks()->init();
