<?php
declare(strict_types=1);
/**
 * Has_Render_Cache trait
 *
 * In-memory render cache helpers shared by all author blocks. Extracted
 * from Author_Block_Base for separation of concerns. The using class must
 * declare a `protected array $render_cache` property which this trait
 * reads and writes via `$this->render_cache`.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait HasRenderCache {

	/**
	 * Get an item from the render cache.
	 *
	 * @param string $cache_key The cache key.
	 *
	 * @return string|null The cached content or null if not found.
	 */
	protected function get_cached_render( string $cache_key ): ?string {
		return $this->render_cache[ $cache_key ] ?? null;
	}

	/**
	 * Set an item in the render cache.
	 *
	 * @param string $cache_key The cache key.
	 * @param string $content   The content to cache.
	 *
	 * @return void
	 */
	protected function set_cached_render( string $cache_key, string $content ): void {
		$this->render_cache[ $cache_key ] = $content;
	}

	/**
	 * Generate a cache key based on data and attributes.
	 *
	 * @param mixed                $identifier A unique identifier (like author ID or array of IDs).
	 * @param array<string, mixed> $attributes The block attributes.
	 *
	 * @return string The cache key.
	 */
	protected function generate_cache_key( $identifier, array $attributes ): string {
		if ( is_array( $identifier ) ) {
			// Sort IDs to ensure consistent cache key regardless of order.
			sort( $identifier );
			$id_string = implode( ',', $identifier );
		} else {
			$id_string = (string) $identifier;
		}

		return md5( $id_string . maybe_serialize( $attributes ) );
	}
}
