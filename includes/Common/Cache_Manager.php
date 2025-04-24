<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Cache Manager Trait
 *
 * @package    AuthorProfileBlocks
 * @subpackage Common
 */

namespace AuthorProfileBlocks\Common;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for managing render caching across blocks.
 */
trait Cache_Manager {
	/**
	 * Cache for rendered content.
	 *
	 * @var array
	 */
	protected array $render_cache = array();

	/**
	 * Check if an item exists in the render cache.
	 *
	 * @param string $cache_key The cache key to check.
	 *
	 * @return bool True if the item exists in cache, false otherwise.
	 */
	protected function has_cached_render( string $cache_key ): bool {
		return isset( $this->render_cache[ $cache_key ] );
	}

	/**
	 * Get an item from the render cache.
	 *
	 * @param string $cache_key The cache key.
	 *
	 * @return string|null The cached content or null if not found.
	 */
	protected function get_cached_render( string $cache_key ): ?string {
		return $this->has_cached_render( $cache_key ) ? $this->render_cache[ $cache_key ] : null;
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
	 * Clear the entire render cache or a specific item.
	 *
	 * @param string|null $cache_key Optional. The cache key to clear. If null, clears the entire cache.
	 *
	 * @return void
	 */
	protected function clear_render_cache( ?string $cache_key = null ): void {
		if ( $cache_key ) {
			unset( $this->render_cache[ $cache_key ] );
		} else {
			$this->render_cache = array();
		}
	}

	/**
	 * Generate a cache key based on data and attributes.
	 *
	 * @param mixed $identifier A unique identifier (like author ID or array of IDs).
	 * @param array $attributes The block attributes.
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
