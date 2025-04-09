<?php
/**
 * Plugin Name:       WP Author Showcase
 * Plugin URI:        https://github.com/mralaminahamed/wp-author-showcase
 * Description:       Showcase author profiles with a beautiful Gutenberg block that displays author information from a custom post type.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Al Amin Ahamed
 * Author URI:        https://github.com/mralaminahamed
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-author-showcase
 * Domain Path:       languages
 *
 * @package WPAuthorShowcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'WPAS_VERSION', '1.0.0' );
define( 'WPAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPAS_PLUGIN_FILE', __FILE__ );

// Autoload classes.
if ( file_exists( WPAS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once WPAS_PLUGIN_DIR . 'vendor/autoload.php';
}

/**
 * Get the plugin instance.
 *
 * @return WPAuthorShowcase\Plugin The plugin instance.
 */
function wpas(): WPAuthorShowcase\Plugin {
	return WPAuthorShowcase\Plugin::get_instance();
}

// Initialize the plugin.
wpas()->init();
