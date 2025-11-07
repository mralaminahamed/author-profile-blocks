<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * User Meta Provider class
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that implements the Meta_Data_Provider interface for WordPress user meta.
 */
class User_Meta_Provider implements Meta_Data_Provider {
	/**
	 * User meta fields configuration.
	 *
	 * @var array
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
	 * @param string $key   The meta field key.
	 * @param array  $config The field configuration.
	 *
	 * @return User_Meta_Provider This instance for method chaining.
	 */
	public function add_meta_field( string $key, array $config ): User_Meta_Provider {
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
