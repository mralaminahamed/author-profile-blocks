<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Block Base
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Common;

use AuthorProfileBlocks\Blocks\Block_Base;
use Author_Profile_Blocks;

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
		return '<div class="apbl-error-message">' . esc_html( $message ) . '</div>';
	}

	/**
	 * Get standardized error message for missing author selection.
	 *
	 * @return string The error message.
	 */
	protected function get_no_authors_selected_message(): string {
		/* translators: %s: block type (grid, carousel, list) */
		return __( 'Please select authors for the %s.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for no authors found.
	 *
	 * @return string The error message.
	 */
	protected function get_no_authors_found_message(): string {
		return __( 'No authors found matching the specified criteria.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for missing single author selection.
	 *
	 * @return string The error message.
	 */
	protected function get_no_author_selected_message(): string {
		return __( 'Please select an author.', 'author-profile-blocks' );
	}

	/**
	 * Get standardized error message for author not found.
	 *
	 * @return string The error message.
	 */
	protected function get_author_not_found_message(): string {
		return __( 'Author not found.', 'author-profile-blocks' );
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

		return Author_Profile_Blocks::get_instance()->get_author_data( $author_id );
	}

	/**
	 * Localize block script with common data.
	 *
	 * @param array $additional_data Optional additional data to include.
	 *
	 * @return void
	 */
	protected function localize_block_script( array $additional_data = array() ): void {
		$default_data = array(
			'adminUrl'  => admin_url(),
			'restNonce' => wp_create_nonce( 'wp_rest' ),
			'restUrl'   => rest_url(),
			'pluginUrl' => APBL_PLUGIN_URL,
		);

		$localized_data = array_merge( $default_data, $additional_data );

		wp_localize_script(
			'author-profile-blocks-' . $this->block_name . '-editor-script',
			'AuthorProfileBlocksData',
			$localized_data
		);
	}

	/**
	 * Extract author IDs from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array Array of author IDs.
	 */
	protected function extract_author_ids( array $attributes ): array {
		return $attributes['authorIds'] ?? array();
	}

	/**
	 * Extract author roles from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return array Array of author roles.
	 */
	protected function extract_author_roles( array $attributes ): array {
		if ( ! empty( $attributes['authorRole'] ) ) {
			return array( $attributes['authorRole'] );
		}
		return array();
	}

	/**
	 * Extract max authors limit from block attributes.
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return int Maximum number of authors.
	 */
	protected function extract_max_authors( array $attributes ): int {
		return isset( $attributes['maxAuthors'] ) ? (int) $attributes['maxAuthors'] : 0;
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
		$plugin  = Author_Profile_Blocks::get_instance();

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
