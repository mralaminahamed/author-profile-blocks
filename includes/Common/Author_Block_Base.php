<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Block Base
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Common;

use AuthorProfileBlocks\Blocks\Block_Base;
use AuthorProfileBlocks\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class for author-related blocks.
 */
abstract class Author_Block_Base extends Block_Base {
	/**
	 * Use the shared traits.
	 */
	use Author_Renderer;
	use Layout_Renderer;
	use Style_Generator;
	use Cache_Manager;

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
	 * Block-specific initialization. Override in child classes if needed.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		// Override in child classes if needed.
	}

	/**
	 * Filter the rendered output of the block.
	 * This is a final processing step after the block renders.
	 *
	 * @param string $block_content The rendered block content.
	 * @param array  $block         The block data.
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
	 * Handles error rendering for author blocks.
	 *
	 * @param string $message The error message.
	 *
	 * @return string HTML for error message.
	 */
	protected function render_error_message( string $message ): string {
		return '<div class="apb-error-message">' . esc_html( $message ) . '</div>';
	}

	/**
	 * Get author data from the service.
	 *
	 * @param int $author_id The author ID.
	 *
	 * @return array|null The author data or null if not found.
	 */
	protected function get_author_data( int $author_id ): ?array {
		if ( empty( $author_id ) ) {
			return null;
		}

		return Plugin::get_instance()->get_author_data( $author_id );
	}

	/**
	 * Get multiple authors data from the service.
	 *
	 * @param array $author_ids Array of author IDs.
	 * @param array $roles      Optional. Roles to filter by.
	 * @param array $args       Optional. Additional arguments.
	 *
	 * @return array Array of author data.
	 */
	protected function get_authors_data( array $author_ids, array $roles = array(), array $args = array() ): array {
		if ( empty( $author_ids ) ) {
			return array();
		}

		$authors = array();
		$plugin  = Plugin::get_instance();

		// First approach: Use individual author data method if we have specific IDs.
		foreach ( $author_ids as $author_id ) {
			$author_data = $plugin->get_author_data( $author_id );
			if ( $author_data ) {
				// Apply role filter if specified.
				if ( ! empty( $roles ) && ! empty( $author_data['role'] ) && ! in_array( $author_data['role'], $roles, true ) ) {
					continue;
				}
				$authors[] = $author_data;
			}
		}

		return $authors;
	}

	/**
	 * Apply maximum author limit.
	 *
	 * @param array $authors     Array of author data.
	 * @param int   $max_authors Maximum number of authors to include.
	 *
	 * @return array Limited author data.
	 */
	protected function apply_author_limit( array $authors, int $max_authors ): array {
		if ( $max_authors > 0 && count( $authors ) > $max_authors ) {
			return array_slice( $authors, 0, $max_authors );
		}

		return $authors;
	}
}
