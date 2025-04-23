<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Base class for common functionality
 *
 * @package AuthorProfileShowcase
 * @subpackage Core
 */

namespace AuthorProfileShowcase\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class that provides common functionality for plugin classes.
 */
abstract class Base {
	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	abstract public function init(): void;
}
