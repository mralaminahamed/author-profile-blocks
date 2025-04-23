<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Base class for common functionality
 *
 * @package AuthorProfileBlocks
 * @subpackage Core
 */

namespace AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class that provides common functionality for plugin classes.
 */
abstract class Base {
	/**
	 * Flag to track initialization state.
	 *
	 * @var bool
	 */
	protected bool $initialized = false;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	abstract public function init(): void;

	/**
	 * Check if this class has been initialized.
	 *
	 * @return bool True if initialized, false otherwise.
	 */
	public function is_initialized(): bool {
		return $this->initialized;
	}

	/**
	 * Set the initialized state.
	 *
	 * @param bool $state The initialization state.
	 * @return void
	 */
	protected function set_initialized( bool $state = true ): void {
		$this->initialized = $state;
	}

	/**
	 * Get the class name without namespace.
	 *
	 * @return string The class name.
	 */
	protected function get_class_name(): string {
		$class_name = get_class( $this );
		if ( preg_match( '/\\\\([^\\\\]+)$/', $class_name, $matches ) ) {
			return $matches[1];
		}
		return $class_name;
	}
}
