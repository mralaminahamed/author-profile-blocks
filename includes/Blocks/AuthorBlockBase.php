<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Block Base
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\Concerns\BuildsBlockClasses;
use AuthorProfileBlocks\Blocks\Concerns\BuildsBlockStyles;
use AuthorProfileBlocks\Blocks\Concerns\HasRenderCache;
use AuthorProfileBlocks\Blocks\Concerns\ProvidesMessages;
use AuthorProfileBlocks\Blocks\Concerns\RendersComponents;
use AuthorProfileBlocks\Blocks\Concerns\RendersLayouts;
use AuthorProfileBlocks\Blocks\Concerns\ResolvesAuthorData;
use AuthorProfileBlocks\Core\Registerable;
use Author_Profile_Blocks;

/**
 * Abstract base class for author-related blocks.
 */
abstract class AuthorBlockBase implements Registerable {
	use ProvidesMessages;
	use ResolvesAuthorData;
	use HasRenderCache;
	use BuildsBlockClasses;
	use BuildsBlockStyles;
	use RendersComponents;
	use RendersLayouts;

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected string $block_name;

	/**
	 * Cache for rendered content.
	 *
	 * @var array<string, string>
	 */
	protected array $render_cache = array();

	/**
	 * Block constructor.
	 *
	 * Initializes the block with its name.
	 */
	public function __construct() {
		$this->block_name = $this->get_block_name();
	}

	/**
	 * Get the source attribute definition for block.json.
	 *
	 * Shared by Phase 2 blocks that support both WP Users and Team Member CPT sources.
	 *
	 * @return array<string,mixed>
	 */
	protected function get_source_attribute(): array {
		return array(
			'type'    => 'string',
			'enum'    => array( 'user', 'team_member' ),
			'default' => 'user',
		);
	}

	/**
	 * Initialize the block.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register();
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
	 * Registers the block using the metadata loaded from the `block.json` file.
	 *
	 * @return void
	 */
	public function register(): void {
		$block_path = APBL_PLUGIN_PATH . 'build/blocks/' . $this->block_name;

		// Check if block.json exists
		if ( ! file_exists( $block_path . '/block.json' ) ) {
			return;
		}

		$args = array();

		// If there's a render callback, add it to the args.
		$callback = $this->get_render_callback();
		if ( $callback ) {
			$args['render_callback'] = $callback;
		}

		// Apply custom block registration filters.
		$args = apply_filters( 'author_profile_blocks_block_args', $args, $this->block_name );

		// Register block using block.json metadata.
		$result = register_block_type( $block_path, $args );

		if ( ! $result ) {
			return;
		}

		// Trigger action after registration.
		do_action( 'author_profile_blocks_block_registered', $this->block_name, $this );
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Register filter for rendered output.
		add_filter( 'render_block_author-profile-blocks/' . $this->block_name, array( $this, 'filter_rendered_output' ), 10, 2 );

		// Add any block-specific initialization.
		$this->block_specific_init();
	}

	/**
	 * Get render callback for the block.
	 *
	 * Child classes should override this method if they need a render callback.
	 * Returns null by default for dynamic blocks that use block.json rendering.
	 *
	 * @return callable|null Block render callback or null.
	 */
	protected function get_render_callback(): ?callable {
		return null;
	}

	/**
	 * Block-specific initialization.
	 *
	 * Override in child classes to add block-specific initialization logic.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		// Override in child classes if needed.
	}

	/**
	 * Filter the rendered output of the block.
	 *
	 * This is a final processing step after the block renders. Applies both
	 * block-specific and general filters to allow customization of output.
	 *
	 * @param string               $block_content The rendered block content.
	 * @param array<string, mixed> $block         {
	 *     The block data including attributes and inner content.
	 *
	 *     @type array<string, mixed> $attributes Block attributes.
	 *     @type string              $innerHTML  Block inner HTML content.
	 *     @type array<string, mixed> $innerBlocks Array of inner blocks.
	 * }
	 *
	 * @return string The filtered content.
	 */
	public function filter_rendered_output( string $block_content, array $block ): string {
		// Apply a block-specific filter first.
		$filtered_content = apply_filters(
			'author_profile_blocks_rendered_' . str_replace( '-', '_', $this->block_name ),
			$block_content,
			$block
		);

		// Then apply a general filter for all blocks.
		return apply_filters( 'author_profile_blocks_rendered_block', $filtered_content, $block, $this->block_name );
	}

	/**
	 * Localize block script with common data.
	 *
	 * Adds JavaScript variables to the block editor script for use in the editor.
	 *
	 * @return void
	 */
	public function localize_block_script(): void {
		$default_data = array(
			'adminUrl'    => admin_url(),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'restUrl'     => rest_url(),
			'pluginUrl'   => plugin_dir_url( APBL_PLUGIN_FILE ),
			'socialIcons' => $this->get_social_icon_data(),
		);

		$localized_data = apply_filters( 'author_profile_blocks_localized_block_data', $default_data );

		wp_localize_script(
			'author-profile-blocks-' . $this->block_name . '-editor-script',
			'AuthorProfileBlocksData',
			$localized_data
		);
	}
}
