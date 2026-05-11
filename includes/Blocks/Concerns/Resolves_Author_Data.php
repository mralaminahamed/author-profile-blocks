<?php
declare(strict_types=1);
/**
 * Resolves_Author_Data trait
 *
 * Author-data resolver helpers shared by all author blocks. Extracts author
 * identifiers, roles, and limits from block attributes, and fetches author
 * records via the plugin service. Extracted from Author_Block_Base for
 * separation of concerns.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Resolves_Author_Data {

	/**
	 * Get author data from the service.
	 *
	 * @param int $author_id The author ID.
	 *
	 * @return array<string, mixed>|null The author data or null if not found.
	 */
	protected function get_author_data( int $author_id ): ?array {
		if ( empty( $author_id ) ) {
			return null;
		}

		return author_profile_blocks()->get_author_data( $author_id );
	}

	/**
	 * Extract author IDs from block attributes.
	 *
	 * @param array<string, mixed> $attributes {
	 *     Block attributes containing authorIds.
	 *
	 *     @type int[] $authorIds Array of author user IDs.
	 * }
	 *
	 * @return int[] Array of author IDs.
	 */
	protected function extract_author_ids( array $attributes ): array {
		return array_map( 'intval', $attributes['authorIds'] ?? array() );
	}

	/**
	 * Extract author roles from block attributes.
	 *
	 * @param array<string, mixed> $attributes {
	 *     Block attributes containing authorRole.
	 *
	 *     @type string $authorRole Single author role to filter by.
	 * }
	 *
	 * @return string[] Array of author roles.
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
	 * @param array<string, mixed> $attributes {
	 *     Block attributes containing maxAuthors.
	 *
	 *     @type int $maxAuthors Maximum number of authors to display.
	 * }
	 *
	 * @return int Maximum number of authors.
	 */
	protected function extract_max_authors( array $attributes ): int {
		return isset( $attributes['maxAuthors'] ) ? (int) $attributes['maxAuthors'] : 0;
	}

	/**
	 * Get multiple authors data from the service.
	 *
	 * @param int[]                $author_ids Array of author IDs.
	 * @param string[]             $roles      Optional. Roles to filter by. Default empty array.
	 * @param array<string, mixed> $args      Optional. Additional arguments for filtering. Default empty array.
	 *
	 * @return array<int, array<string, mixed>> Array of author data. Each item contains author information.
	 */
	protected function get_authors_data( array $author_ids, array $roles = array(), array $args = array() ): array {
		if ( empty( $author_ids ) ) {
			return array();
		}

		$authors = array();

		// First approach: Use individual author data method if we have specific IDs.
		foreach ( $author_ids as $author_id ) {
			$author_data = author_profile_blocks()->get_author_data( $author_id );
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
	 * @param array<int, array<string, mixed>> $authors     Array of author data. Each item contains author information.
	 * @param int                              $max_authors Maximum number of authors to include. 0 means no limit.
	 *
	 * @return array<int, array<string, mixed>> Limited author data.
	 */
	protected function apply_author_limit( array $authors, int $max_authors ): array {
		if ( $max_authors > 0 && count( $authors ) > $max_authors ) {
			return array_slice( $authors, 0, $max_authors );
		}

		return $authors;
	}
}
