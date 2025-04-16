<?php
/**
 * Abstract Block Base class
 *
 * @package WPAuthorShowcase
 * @subpackage Blocks
 */

namespace WPAuthorShowcase\Blocks;

use function add_action;
use function register_block_type;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract class for block registration.
 */
abstract class Block_Base {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected string $block_name;

	/**
	 * Block constructor.
	 */
	public function __construct() {
		$this->block_name = $this->get_block_name();
	}

	/**
	 * Initialize the block.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register_block' ) );

		// Allow child classes to add additional initialization.
		$this->additional_init();
	}

	/**
	 * Get the block name.
	 *
	 * This is used both internally and externally to identify the block.
	 *
	 * @return string Block name.
	 */
	abstract public function get_block_name(): string;

	/**
	 * Additional initialization actions for the block.
	 * Child classes can override this to add custom actions.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Override in child classes if needed.
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 *
	 * @return void
	 */
	public function register_block(): void {
		$args = array();

		// If there's a render callback, add it to the args.
		$callback = $this->get_render_callback();
		if ( $callback ) {
			$args['render_callback'] = $callback;
		}

		// Register block using block.json metadata.
		register_block_type(
			WPAS_PLUGIN_DIR . 'build/blocks/' . $this->block_name,
			$args
		);
	}

	/**
	 * Get render callback for the block.
	 * Child classes should override this method if they need a callback.
	 *
	 * @return callable|null Block render callback or null.
	 */
	protected function get_render_callback(): ?callable {
		return null;
	}
}
