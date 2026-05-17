<?php
/**
 * Author Profile Blocks
 *
 * @package           AuthorProfileBlocks
 * @author            Al Amin Ahamed
 * @copyright         2025 Al Amin Ahamed
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Author Profile Blocks
 * Plugin URI:        https://github.com/mralaminahamed/author-profile-blocks
 * Description:       Gutenberg blocks for displaying author profiles and team members with customizable layouts.
 * Version:           1.0.4
 * Requires at least: 6.0
 * Tested up to:      6.9
 * Requires PHP:      7.4
 * Author:            Al Amin Ahamed
 * Author URI:        https://github.com/mralaminahamed
 * Text Domain:       author-profile-blocks
 * Domain Path:       /languages
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.ShortPrefixPassed
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'APBL_VERSION', '1.0.4' );
define( 'APBL_PLUGIN_FILE', __FILE__ );
define( 'APBL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'APBL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Load Composer autoloader for PSR-4 classes
if ( ! file_exists( APBL_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	return;
}

require_once APBL_PLUGIN_PATH . 'vendor/autoload.php';

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
