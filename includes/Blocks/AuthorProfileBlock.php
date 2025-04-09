<?php
/**
 * Author Profile Block class
 *
 * @package WPAuthorShowcase
 * @subpackage Blocks
 */

namespace WPAuthorShowcase\Blocks;

use function add_action;
use function register_block_type;
use function wp_register_block_metadata_collection;
use function wp_register_block_types_from_metadata_collection;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class AuthorProfileBlock {
	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register block.
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Register the block.
	 *
	 * @return void
	 */
	public function register_block(): void {
		// Check for new block registration method.
		if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
			wp_register_block_types_from_metadata_collection( WPAS_PLUGIN_DIR . 'build', WPAS_PLUGIN_DIR . 'build/blocks-manifest.php' );
			return;
		}

		// Check for WP 6.7+ block registration method.
		if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
			wp_register_block_metadata_collection( WPAS_PLUGIN_DIR . 'build', WPAS_PLUGIN_DIR . 'build/blocks-manifest.php' );
		}

		// Fallback to registering each block type.
		$manifest_file = WPAS_PLUGIN_DIR . 'build/blocks-manifest.php';
		if ( file_exists( $manifest_file ) ) {
			$manifest_data = require $manifest_file;
			foreach ( array_keys( $manifest_data ) as $block_type ) {
				register_block_type( WPAS_PLUGIN_DIR . "build/$block_type" );
			}
		}
	}
}
