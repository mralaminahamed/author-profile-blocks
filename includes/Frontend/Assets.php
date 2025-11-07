<?php

/**
 * Frontend Assets Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Assets Class
 *
 * @package AuthorProfileBlocks
 */

/**
 * Frontend Assets Class
 */
class Assets {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue frontend scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		// Only enqueue on pages that might have our blocks
		if ( has_block( 'author-profile-blocks/author-profile' ) ||
			has_block( 'author-profile-blocks/author-grid' ) ||
			has_block( 'author-profile-blocks/author-list' ) ||
			has_block( 'author-profile-blocks/author-carousel' ) ) {

			wp_enqueue_style(
				'author-profile-blocks-frontend',
				plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/frontend/styles.css',
				array(),
				APBL_VERSION,
				'all'
			);
		}
	}
}
