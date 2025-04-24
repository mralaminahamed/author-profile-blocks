<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Meta Data Provider interface
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for classes that provide meta data.
 */
interface Meta_Data_Provider {
	/**
	 * Register meta fields.
	 *
	 * @return void
	 */
	public function register_meta_fields(): void;

	/**
	 * Get meta data for a specific item.
	 *
	 * @param int    $item_id The ID of the item.
	 * @param string $key     The meta key.
	 * @param bool   $single  Whether to return a single value.
	 *
	 * @return mixed The meta value(s).
	 */
	public function get_meta( int $item_id, string $key, bool $single = true );

	/**
	 * Update meta data for a specific item.
	 *
	 * @param int    $item_id The ID of the item.
	 * @param string $key     The meta key.
	 * @param mixed  $value   The meta value.
	 *
	 * @return bool|int Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	public function update_meta( int $item_id, string $key, $value );
}
