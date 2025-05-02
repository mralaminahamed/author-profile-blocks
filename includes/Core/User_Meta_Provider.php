<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * User Meta Provider class
 *
 * @package AuthorProfileBlocks
 */

namespace APBL\AuthorProfileBlocks\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that implements the Meta_Data_Provider interface for WordPress user meta.
 */
class User_Meta_Provider extends Base implements Meta_Data_Provider {
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
		add_action( 'init', array( $this, 'register_meta_fields' ) );
	}

	/**
	 * Initialize the provider.
	 *
	 * @return void
	 */
	public function init(): void {
		// Set initialized state.
		$this->set_initialized();
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
	 * @param string $key    The meta key.
	 * @param array  $config The meta configuration.
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

	/**
	 * Delete meta data for a specific user.
	 *
	 * @param int    $item_id The user ID.
	 * @param string $key     The meta key.
	 * @param mixed  $value   Optional. The meta value to delete.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete_meta( int $item_id, string $key, $value = '' ): bool {
		return delete_user_meta( $item_id, $key, $value );
	}

	/**
	 * Check if a meta field exists.
	 *
	 * @param string $key The meta key.
	 *
	 * @return bool True if the meta field exists, false otherwise.
	 */
	public function has_meta_field( string $key ): bool {
		return isset( $this->meta_fields[ $key ] );
	}

	/**
	 * Get all registered meta fields.
	 *
	 * @return array The meta fields.
	 */
	public function get_meta_fields(): array {
		return $this->meta_fields;
	}
}
