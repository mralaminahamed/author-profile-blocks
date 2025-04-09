<?php
/**
 * Main plugin class
 *
 * @package WPAuthorShowcase
 */

namespace WPAuthorShowcase;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class that initializes all functionality.
 */
class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Plugin modules.
	 *
	 * @var array
	 */
	private array $modules;

	/**
	 * Private constructor to prevent direct object creation.
	 */
	private function __construct() {
		// Initialize modules.
		$this->modules = [
			new PostTypes\AuthorProfile(),
			new Blocks\AuthorProfileBlock(),
		];
	}

	/**
	 * Get plugin instance.
	 *
	 * @return Plugin The plugin instance.
	 */
	public static function get_instance(): ?Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register plugin hooks.
		$this->register_hooks();

		// Initialize modules.
		$this->initialize_modules();
	}

	/**
	 * Register plugin hooks.
	 *
	 * @return void
	 */
	private function register_hooks(): void {
		// Nothing to do here yet.
	}

	/**
	 * Initialize plugin modules.
	 *
	 * @return void
	 */
	private function initialize_modules(): void {
		foreach ( $this->modules as $module ) {
			if ( method_exists( $module, 'init' ) ) {
				$module->init();
			}
		}
	}
}
