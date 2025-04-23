<?php
/**
 * Plugin Name:       Author Profile Showcase
 * Plugin URI:        https://github.com/mralaminahamed/author-profile-showcase
 * Description:       Showcase author profiles with a beautiful Gutenberg block that displays author information from a custom post type.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Al Amin Ahamed
 * Author URI:        https://github.com/mralaminahamed
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       author-profile-showcase
 * Domain Path:       languages
 *
 * @package AuthorProfileShowcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'APS_VERSION', '1.0.0' );
define( 'APS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'APS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'APS_PLUGIN_FILE', __FILE__ );

if ( ! file_exists( APS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	return;
}

// Autoload classes.
require_once APS_PLUGIN_DIR . 'vendor/autoload.php';

/**
 * Get the plugin instance.
 *
 * @return AuthorProfileShowcase\Plugin The plugin instance.
 */
function aps(): AuthorProfileShowcase\Plugin {
	return AuthorProfileShowcase\Plugin::get_instance();
}

// Initialize the plugin.
aps()->init();
