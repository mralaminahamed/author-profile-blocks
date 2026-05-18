<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Shortcode Registry class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Shortcodes;

use AuthorProfileBlocks\Core\Registerable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Collects and registers all plugin shortcode instances.
 */
class ShortcodeRegistry implements Registerable {

	/**
	 * Registered shortcode instances.
	 *
	 * @var object[]
	 */
	private array $shortcodes = array();

	/**
	 * Add a shortcode instance to the registry.
	 *
	 * @param object $shortcode The shortcode instance.
	 * @return void
	 */
	public function add( object $shortcode ): void {
		$this->shortcodes[] = $shortcode;
	}

	/**
	 * Register the registry with WordPress.
	 *
	 * @return void
	 */
	public function register(): void {
		$this->init();
	}

	/**
	 * Hook shortcode registration to the init action.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register_all' ) );
	}

	/**
	 * Register all shortcodes.
	 *
	 * @return void
	 */
	public function register_all(): void {
		foreach ( $this->shortcodes as $shortcode ) {
			$shortcode->register();
		}
	}
}
