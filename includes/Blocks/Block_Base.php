<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Abstract Block Base class
 *
 * @package    AuthorProfileBlocks
 * @subpackage Blocks
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Core\Base;
use AuthorProfileBlocks\Core\Registerable;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract class for block registration.
 */
abstract class Block_Base extends Base implements Registerable {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected string $block_name;

	/**
	 * Registration state.
	 *
	 * @var bool
	 */
	protected bool $registered = false;

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
		// Register the block during WordPress init.
		add_action( 'init', array( $this, 'register' ) );

		// Allow child classes to add additional initialization.
		$this->additional_init();

		// Set initialized state.
		$this->set_initialized();
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
	public function register(): void {
		$args = array();

		// If there's a render callback, add it to the args.
		$callback = $this->get_render_callback();
		if ( $callback ) {
			$args['render_callback'] = $callback;
		}

		// Apply custom block registration filters.
		$args = apply_filters( 'author_profile_blocks_block_args', $args, $this->block_name );

		// Register block using block.json metadata.
		register_block_type(
			APB_PLUGIN_DIR . 'build/blocks/' . $this->block_name,
			$args
		);

		// Set registration state.
		$this->registered = true;

		// Trigger action after registration.
		do_action( 'author_profile_blocks_block_registered', $this->block_name, $this );
	}

	/**
	 * Check if the block is registered.
	 *
	 * @return bool True if registered, false otherwise.
	 */
	public function is_registered(): bool {
		return $this->registered;
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

	/**
	 * Get the asset path for the current block.
	 *
	 * @param string $asset_name The asset name.
	 * @param string $extension  The file extension.
	 *
	 * @return string The asset path.
	 */
	protected function get_asset_path( string $asset_name, string $extension = 'js' ): string {
		return APB_PLUGIN_URL . 'build/blocks/' . $this->block_name . '/' . $asset_name . '.' . $extension;
	}
}
