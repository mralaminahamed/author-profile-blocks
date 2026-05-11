<?php
declare(strict_types=1);
/**
 * Provides_Messages trait
 *
 * Standardised user-facing error / status messages shared by all author
 * blocks. Extracted from Author_Block_Base for separation of concerns.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait ProvidesMessages {

	/**
	 * Render an error message for author blocks.
	 *
	 * @param string $message The error message to display.
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
}
