<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Block Registry class
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Core\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles block registration.
 */
class Block_Registry extends Base {
	/**
	 * List of blocks to register.
	 *
	 * @var Block_Base[]
	 */
	private array $blocks = array();

	/**
	 * Initialize the registry.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register_blocks();
		$this->initialize_blocks();

		// Allow other components to interact with our block registry.
		do_action( 'author_profile_blocks_blocks_registered', $this );
	}

	/**
	 * Register all blocks.
	 *
	 * @return void
	 */
	private function register_blocks(): void {
		// Register all blocks here.
		$this->register_block( new Author_Profile_Block() );
		$this->register_block( new Author_Grid_Block() );
		$this->register_block( new Author_Carousel_Block() );
		$this->register_block( new Author_List_Block() );

		// Allow plugins/themes to register additional blocks.
		do_action( 'author_profile_blocks_register_blocks', $this );
	}

	/**
	 * Initialize all registered blocks.
	 *
	 * @return void
	 */
	private function initialize_blocks(): void {
		// Initialize each block.
		foreach ( $this->blocks as $block ) {
			$block->init();
		}
	}

	/**
	 * Register a block instance.
	 *
	 * @param Block_Base $block Block instance.
	 *
	 * @return void
	 */
	public function register_block( Block_Base $block ): void {
		$this->blocks[] = $block;
	}

	/**
	 * Get all registered blocks.
	 *
	 * @return Block_Base[] Array of block instances.
	 */
	public function get_blocks(): array {
		return $this->blocks;
	}

	/**
	 * Get a specific block by name.
	 *
	 * @param string $name Block name.
	 *
	 * @return Block_Base|null Block instance or null if not found.
	 */
	public function get_block( string $name ): ?Block_Base {
		foreach ( $this->blocks as $block ) {
			if ( $block->get_block_name() === $name ) {
				return $block;
			}
		}

		return null;
	}
}
