<?php
/**
 * Plugin Name:       Author Profile Blocks
 * Plugin URI:        https://github.com/mralaminahamed/author-profile-blocks
 * Description:       A collection of powerful Gutenberg blocks for showcasing author profiles and team members using WordPress users.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Al Amin Ahamed
 * Author URI:        https://github.com/mralaminahamed
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
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
define( 'APBL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'APBL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'APBL_PLUGIN_FILE', __FILE__ );

if ( ! file_exists( APBL_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	return;
}

// Autoload classes.
require_once APBL_PLUGIN_DIR . 'vendor/autoload.php';

/**
 * Get the plugin instance.
 *
 * @return APBL\AuthorProfileBlocks\Plugin The plugin instance.
 */
function apbl(): APBL\AuthorProfileBlocks\Plugin {
	return APBL\AuthorProfileBlocks\Plugin::get_instance();
}

// Take off.
apbl()->init();
