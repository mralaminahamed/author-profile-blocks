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
		$this->modules = array(
			new Post_Types\Author_Profile(),
			new Blocks\Author_Profile_Block(),
		);
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
		$this->initialize_modules();
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
