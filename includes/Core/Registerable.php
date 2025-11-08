<?php

/**
 * Registerable interface
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for classes that need to be registered with WordPress.
 */
interface Registerable {
	/**
	 * Register with WordPress.
	 *
	 * @return void
	 */
	public function register(): void;
}
