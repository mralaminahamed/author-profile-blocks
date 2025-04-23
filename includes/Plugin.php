<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin class
 *
 * @package AuthorProfileShowcase
 */

namespace AuthorProfileShowcase;

use AuthorProfileShowcase\Blocks\Block_Registry;
use AuthorProfileShowcase\Core\Base;
use AuthorProfileShowcase\Post_Types\Author_Profile_CPT;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Plugin extends Base {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Block registry instance.
	 *
	 * @var Block_Registry
	 */
	private Block_Registry $block_registry;

	/**
	 * Author Profile CPT instance.
	 *
	 * @var Author_Profile_CPT
	 */
	private Author_Profile_CPT $author_profile_cpt;

	/**
	 * Get plugin instance.
	 *
	 * @return Plugin Plugin instance.
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->block_registry     = new Block_Registry();
		$this->author_profile_cpt = new Author_Profile_CPT();
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize custom post type.
		$this->author_profile_cpt->init();

		// Initialize blocks.
		$this->block_registry->init();

		// Load text domain.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @return void
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain(
			'author-profile-showcase',
			false,
			dirname( plugin_basename( WPAS_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Get the block registry.
	 *
	 * @return Block_Registry Block registry instance.
	 */
	public function get_block_registry(): Block_Registry {
		return $this->block_registry;
	}

	/**
	 * Get the author profile CPT.
	 *
	 * @return Author_Profile_CPT Author Profile CPT instance.
	 */
	public function get_author_profile_cpt(): Author_Profile_CPT {
		return $this->author_profile_cpt;
	}
}
