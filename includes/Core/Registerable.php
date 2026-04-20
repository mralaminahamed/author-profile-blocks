<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Registerable interface
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
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
