<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Service class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Services;

use AuthorProfileBlocks\Core\User_Meta_Provider;
use WP_User_Query;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles author profile data operations.
 */
class Author_Profile_Service {
	/**
	 * User Meta Provider instance.
	 *
	 * @var User_Meta_Provider
	 */
	private User_Meta_Provider $meta_provider;

	/**
	 * Cache for author data.
	 *
	 * @var array
	 */
	private array $author_cache = array();

	/**
	 * Constructor.
	 *
	 * @param User_Meta_Provider $meta_provider The user meta provider.
	 */
	public function __construct( User_Meta_Provider $meta_provider ) {
		$this->meta_provider = $meta_provider;
	}

	/**
	 * Initialize the service.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register REST API field extensions.
		add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );

		// Add hook for user updates to clear cache.
		add_action( 'profile_update', array( $this, 'clear_user_cache' ) );
		add_action( 'user_register', array( $this, 'clear_user_cache' ) );
		add_action( 'deleted_user', array( $this, 'clear_user_cache' ) );

		// Initialization complete
	}

	/**
	 * Register REST API fields to enhance user data.
	 *
	 * @return void
	 */
	public function register_rest_fields(): void {
		// Register avatar URL field.
		register_rest_field(
			'user',
			'avatar_url',
			array(
				'get_callback'    => array( $this, 'get_avatar_url' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Avatar URL', 'author-profile-blocks' ),
					'type'        => 'string',
				),
			)
		);

		// Register position field.
		register_rest_field(
			'user',
			'author_position',
			array(
				'get_callback'    => array( $this, 'get_author_position' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Author position or title', 'author-profile-blocks' ),
					'type'        => 'string',
				),
			)
		);

		// Register description field.
		register_rest_field(
			'user',
			'author_description',
			array(
				'get_callback'    => array( $this, 'get_author_description' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Author extended description', 'author-profile-blocks' ),
					'type'        => 'string',
				),
			)
		);

		// Register social profiles field.
		register_rest_field(
			'user',
			'social_profiles',
			array(
				'get_callback'    => array( $this, 'get_social_profiles' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Author social profiles', 'author-profile-blocks' ),
					'type'        => 'object',
				),
			)
		);

		// Register registered date field.
		register_rest_field(
			'user',
			'registered_date',
			array(
				'get_callback'    => array( $this, 'get_registered_date' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'User registration date', 'author-profile-blocks' ),
					'type'        => 'string',
				),
			)
		);

		// Register member since label field.
		register_rest_field(
			'user',
			'member_since_label',
			array(
				'get_callback'    => array( $this, 'get_member_since_label' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Member since label', 'author-profile-blocks' ),
					'type'        => 'string',
				),
			)
		);

		// Allow other components to register additional fields.
		do_action( 'author_profile_blocks_register_rest_fields', $this );
	}



	/**
	 * Get user avatar URL for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return string Avatar URL.
	 */
	public function get_avatar_url( array $user ): string {
		return get_avatar_url( $user['id'], array( 'size' => 150 ) );
	}

	/**
	 * Get author position for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return string Author position.
	 */
	public function get_author_position( array $user ): string {
		return $this->meta_provider->get_meta( $user['id'], 'apbl_author_position', true ) ?? '';
	}

	/**
	 * Get author description for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return string Author description.
	 */
	public function get_author_description( array $user ): string {
		return $this->meta_provider->get_meta( $user['id'], 'apbl_author_description', true ) ?? '';
	}

	/**
	 * Get social profiles for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return array Social profiles.
	 */
	public function get_social_profiles( array $user ): array {
		$profiles = $this->meta_provider->get_meta( $user['id'], 'apbl_social_profiles', true );

		if ( ! is_array( $profiles ) ) {
			$profiles = array(
				'facebook'  => '',
				'twitter'   => '',
				'linkedin'  => '',
				'instagram' => '',
				'website'   => '',
			);
		}

		return $profiles;
	}

	/**
	 * Get user registration date for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return string Formatted registration date.
	 */
	public function get_registered_date( array $user ): string {
		$user_obj = get_userdata( $user['id'] );
		if ( ! $user_obj ) {
			return '';
		}

		// Format the registration date according to the site's date format setting.
		return date_i18n( get_option( 'date_format' ), strtotime( $user_obj->user_registered ) );
	}

	/**
	 * Get custom member since label for REST API.
	 *
	 * @param array $user User data.
	 *
	 * @return string Member since label.
	 */
	public function get_member_since_label( array $user ): string {
		$custom_label = $this->meta_provider->get_meta( $user['id'], 'apbl_member_since_label', true );

		// Use default if empty.
		if ( empty( $custom_label ) ) {
			$custom_label = __( 'Member since', 'author-profile-blocks' );
		}

		return $custom_label;
	}

	/**
	 * Get author data by ID
	 *
	 * @param int $author_id User ID.
	 *
	 * @return array|null Author data or null if not found
	 */
	public function get_author_data( int $author_id ): ?array {
		// Check cache first.
		if ( isset( $this->author_cache[ $author_id ] ) ) {
			return $this->author_cache[ $author_id ];
		}

		$user = get_user_by( 'id', $author_id );

		if ( ! $user ) {
			return null;
		}

		$description     = $this->meta_provider->get_meta( $author_id, 'apbl_author_description', true );
		$position        = $this->meta_provider->get_meta( $author_id, 'apbl_author_position', true );
		$social_profiles = $this->meta_provider->get_meta( $author_id, 'apbl_social_profiles', true );
		$image           = get_avatar_url( $author_id, array( 'size' => 150 ) );

		// Get member since label (use default if custom label is not set).
		$member_since_label = $this->meta_provider->get_meta( $author_id, 'apbl_member_since_label', true );
		if ( empty( $member_since_label ) ) {
			$member_since_label = __( 'Member since', 'author-profile-blocks' );
		}

		// Get registration date.
		$registered_date = date_i18n( get_option( 'date_format' ), strtotime( $user->user_registered ) );

		$author_data = array(
			'id'                 => $author_id,
			'title'              => $user->display_name,
			'email'              => $user->user_email,
			'description'        => $description,
			'position'           => $position,
			'social'             => $social_profiles,
			'image'              => $image,
			'registered_date'    => $registered_date,
			'member_since_label' => $member_since_label,
			'role'               => $user->roles[0] ?? '',
		);

		// Apply filters to allow customization of author data.
		$author_data = apply_filters( 'author_profile_blocks_author_data', $author_data, $user );

		// Cache the result.
		$this->author_cache[ $author_id ] = $author_data;

		return $author_data;
	}

	/**
	 * Get all authors with specific roles.
	 *
	 * @param array $roles Optional. Roles to include. Default is all author-type roles.
	 * @param array $args  Optional. Additional arguments for WP_User_Query.
	 *
	 * @return array Array of author data.
	 */
	public function get_authors( array $roles = array(), array $args = array() ): array {
		// Set default roles if none provided.
		if ( empty( $roles ) ) {
			$roles = array( 'administrator', 'editor', 'author', 'contributor' );
		}

		// Create a cache key for this query.
		$cache_key = md5(
			maybe_serialize(
				array(
					'roles' => $roles,
					'args'  => $args,
				)
			)
		);

		// Check for cached results.
		$cached_results = wp_cache_get( $cache_key, 'author_profile_blocks_authors' );
		if ( false !== $cached_results ) {
			return $cached_results;
		}

		// Merge with default arguments.
		$query_args = wp_parse_args(
			$args,
			array(
				'role__in'    => $roles,
				'orderby'     => 'display_name',
				'order'       => 'ASC',
				'count_total' => false,
				'number'      => - 1, // Get all users.
			)
		);

		// Allow filtering of query args.
		$query_args = apply_filters( 'author_profile_blocks_author_query_args', $query_args );

		// Get users.
		$user_query = new WP_User_Query( $query_args );
		$users      = $user_query->get_results();
		$authors    = array();

		// Build author data.
		foreach ( $users as $user ) {
			$author_data = $this->get_author_data( $user->ID );
			if ( $author_data ) {
				$authors[] = $author_data;
			}
		}

		// Allow filtering of authors.
		$authors = apply_filters( 'author_profile_blocks_authors', $authors, $query_args );

		// Cache the results.
		wp_cache_set( $cache_key, $authors, 'author_profile_blocks_authors', HOUR_IN_SECONDS );

		return $authors;
	}





	/**
	 * Clear the author cache when a user is updated.
	 *
	 * @param int $user_id The user ID that was updated.
	 *
	 * @return void
	 */
	public function clear_user_cache( int $user_id ): void {
		// Clear this specific user from the cache.
		$this->clear_cache( $user_id );
	}

	/**
	 * Clear the author cache.
	 *
	 * @param int|null $author_id Optional. The author ID to clear from cache.
	 *
	 * @return void
	 */
	public function clear_cache( ?int $author_id = null ): void {
		if ( $author_id ) {
			unset( $this->author_cache[ $author_id ] );
		} else {
			$this->author_cache = array();
		}
	}
}
