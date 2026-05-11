<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * User Meta Provider class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that implements the MetaDataProvider interface for WordPress user meta.
 */
class UserMetaProvider implements MetaDataProvider {
	/**
	 * User meta fields configuration.
	 *
	 * @var array<string, mixed>
	 */
	protected array $meta_fields = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Meta field registration is handled by the main plugin.
	}

	/**
	 * Initialize the provider.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialization complete
	}

	/**
	 * Register meta fields.
	 *
	 * @return void
	 */
	public function register_meta_fields(): void {
		// Register each configured meta field.
		foreach ( $this->meta_fields as $key => $config ) {
			register_meta(
				'user',
				$key,
				$config
			);
		}
	}

	/**
	 * Add a meta field configuration.
	 *
	 * @param string               $key   The meta field key.
	 * @param array<string, mixed> $config {
	 *     The field configuration for register_meta().
	 *
	 *     @type string   $type              Data type (string, integer, etc.).
	 *     @type bool     $single            Whether to return single value.
	 *     @type mixed    $default           Default value.
	 *     @type callable $sanitize_callback Sanitization callback.
	 *     @type callable $auth_callback     Authorization callback.
	 *     @type bool     $show_in_rest      Whether to show in REST API.
	 * }
	 *
	 * @return UserMetaProvider This instance for method chaining.
	 */
	public function add_meta_field( string $key, array $config ): UserMetaProvider {
		$this->meta_fields[ $key ] = $config;

		return $this;
	}

	/**
	 * Get meta data for a specific user.
	 *
	 * @param int    $item_id The user ID.
	 * @param string $key     The meta key.
	 * @param bool   $single  Whether to return a single value.
	 *
	 * @return mixed The meta value(s).
	 */
	public function get_meta( int $item_id, string $key, bool $single = true ) {
		return get_user_meta( $item_id, $key, $single );
	}

	/**
	 * Update meta data for a specific user.
	 *
	 * @param int    $item_id The user ID.
	 * @param string $key     The meta key.
	 * @param mixed  $value   The meta value.
	 *
	 * @return bool|int Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	public function update_meta( int $item_id, string $key, $value ) {
		return update_user_meta( $item_id, $key, $value );
	}
}
