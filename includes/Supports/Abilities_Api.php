<?php
/**
 * Abilities API integration for Author Profile Blocks
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Supports;

use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register plugin abilities with the WordPress Abilities API
 */
class Abilities_Api {

	/**
	 * Initialize the abilities registration
	 */
	public static function init(): void {
		add_action( 'wp_abilities_api_init', array( self::class, 'register_abilities' ), 20 );
	}

	/**
	 * Register all plugin abilities
	 *
	 * Note: This implementation is prepared for the future WordPress Abilities API.
	 * Currently, the wp_register_ability function does not exist in WordPress core.
	 * When the Abilities API is released, this code will automatically become active.
	 */
	public static function register_abilities(): void {
		// Check if the Abilities API is available (future WordPress feature)
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		// Ability: Get author data
		wp_register_ability(
			'author-profile-blocks/get-author-data',
			array(
				'label'               => __( 'Get Author Data', 'author-profile-blocks' ),
				'description'         => __( 'Retrieves detailed information about a specific WordPress user/author.', 'author-profile-blocks' ),
				'thinking_message'    => __( 'Fetching author information...', 'author-profile-blocks' ),
				'success_message'     => __( 'Author data retrieved successfully.', 'author-profile-blocks' ),
				'execute_callback'    => array( self::class, 'get_author_data_ability' ),
				'input_schema'        => array(
					'type'                  => 'object',
					'properties'            => array(
						'author_id'    => array(
							'type'        => 'integer',
							'description' => __( 'The WordPress user ID of the author.', 'author-profile-blocks' ),
							'required'    => true,
						),
						'include_meta' => array(
							'type'        => 'boolean',
							'description' => __( 'Whether to include custom metadata like social profiles.', 'author-profile-blocks' ),
							'default'     => true,
						),
					),
					'additional_properties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'          => array(
							'type'        => 'integer',
							'description' => __( 'User ID.', 'author-profile-blocks' ),
						),
						'name'        => array(
							'type'        => 'string',
							'description' => __( 'Display name.', 'author-profile-blocks' ),
						),
						'email'       => array(
							'type'        => 'string',
							'description' => __( 'Email address.', 'author-profile-blocks' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __( 'User bio/description.', 'author-profile-blocks' ),
						),
						'avatar'      => array(
							'type'        => 'string',
							'description' => __( 'Avatar URL.', 'author-profile-blocks' ),
						),
						'social'      => array(
							'type'        => 'object',
							'description' => __( 'Social media profiles.', 'author-profile-blocks' ),
							'properties'  => array(
								'facebook'  => array( 'type' => 'string' ),
								'twitter'   => array( 'type' => 'string' ),
								'linkedin'  => array( 'type' => 'string' ),
								'instagram' => array( 'type' => 'string' ),
								'website'   => array( 'type' => 'string' ),
							),
						),
						'role'        => array(
							'type'        => 'string',
							'description' => __( 'User role.', 'author-profile-blocks' ),
						),
					),
					'required'   => array( 'id', 'name' ),
				),
				'permission_callback' => function () {
					return current_user_can( 'list_users' );
				},
			)
		);

		// Ability: Filter authors by criteria
		wp_register_ability(
			'author-profile-blocks/filter-authors',
			array(
				'label'               => __( 'Filter Authors', 'author-profile-blocks' ),
				'description'         => __( 'Retrieves a list of authors filtered by role, capabilities, or other criteria.', 'author-profile-blocks' ),
				'thinking_message'    => __( 'Searching for authors matching your criteria...', 'author-profile-blocks' ),
				'success_message'     => __( 'Authors filtered successfully.', 'author-profile-blocks' ),
				'execute_callback'    => array( self::class, 'filter_authors_ability' ),
				'input_schema'        => array(
					'type'                  => 'object',
					'properties'            => array(
						'role'       => array(
							'type'        => 'string',
							'description' => __( 'Filter by user role (administrator, editor, author, etc.).', 'author-profile-blocks' ),
							'enum'        => array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' ),
						),
						'capability' => array(
							'type'        => 'string',
							'description' => __( 'Filter by specific capability.', 'author-profile-blocks' ),
						),
						'has_posts'  => array(
							'type'        => 'boolean',
							'description' => __( 'Only include users who have published posts.', 'author-profile-blocks' ),
							'default'     => false,
						),
						'limit'      => array(
							'type'        => 'integer',
							'description' => __( 'Maximum number of authors to return.', 'author-profile-blocks' ),
							'minimum'     => 1,
							'maximum'     => 100,
							'default'     => 10,
						),
					),
					'additional_properties' => false,
				),
				'output_schema'       => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'id'     => array(
								'type'        => 'integer',
								'description' => __( 'User ID.', 'author-profile-blocks' ),
							),
							'name'   => array(
								'type'        => 'string',
								'description' => __( 'Display name.', 'author-profile-blocks' ),
							),
							'email'  => array(
								'type'        => 'string',
								'description' => __( 'Email address.', 'author-profile-blocks' ),
							),
							'role'   => array(
								'type'        => 'string',
								'description' => __( 'User role.', 'author-profile-blocks' ),
							),
							'avatar' => array(
								'type'        => 'string',
								'description' => __( 'Avatar URL.', 'author-profile-blocks' ),
							),
						),
						'required'   => array( 'id', 'name' ),
					),
				),
				'permission_callback' => function () {
					return current_user_can( 'list_users' );
				},
			)
		);

		// Ability: Render author profile block
		wp_register_ability(
			'author-profile-blocks/render-author-profile',
			array(
				'label'               => __( 'Render Author Profile Block', 'author-profile-blocks' ),
				'description'         => __( 'Generates HTML for an author profile block with customizable styling and content.', 'author-profile-blocks' ),
				'thinking_message'    => __( 'Creating author profile display...', 'author-profile-blocks' ),
				'success_message'     => __( 'Author profile rendered successfully.', 'author-profile-blocks' ),
				'execute_callback'    => array( self::class, 'render_author_profile_ability' ),
				'input_schema'        => array(
					'type'                  => 'object',
					'properties'            => array(
						'author_id'        => array(
							'type'        => 'integer',
							'description' => __( 'The WordPress user ID to display.', 'author-profile-blocks' ),
							'required'    => true,
						),
						'show_image'       => array(
							'type'        => 'boolean',
							'description' => __( 'Whether to show the author avatar.', 'author-profile-blocks' ),
							'default'     => true,
						),
						'show_name'        => array(
							'type'        => 'boolean',
							'description' => __( 'Whether to show the author name.', 'author-profile-blocks' ),
							'default'     => true,
						),
						'show_description' => array(
							'type'        => 'boolean',
							'description' => __( 'Whether to show the author bio.', 'author-profile-blocks' ),
							'default'     => true,
						),
						'show_social'      => array(
							'type'        => 'boolean',
							'description' => __( 'Whether to show social media links.', 'author-profile-blocks' ),
							'default'     => true,
						),
						'layout'           => array(
							'type'        => 'string',
							'description' => __( 'Layout style for the profile.', 'author-profile-blocks' ),
							'enum'        => array( 'default', 'card', 'minimal', 'centered' ),
							'default'     => 'default',
						),
					),
					'additional_properties' => false,
				),
				'output_schema'       => array(
					'type'        => 'string',
					'description' => __( 'HTML output for the author profile block.', 'author-profile-blocks' ),
				),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		// Ability: Update author metadata
		wp_register_ability(
			'author-profile-blocks/update-author-meta',
			array(
				'label'               => __( 'Update Author Metadata', 'author-profile-blocks' ),
				'description'         => __( 'Updates custom metadata for an author, such as social profiles or additional information.', 'author-profile-blocks' ),
				'thinking_message'    => __( 'Updating author information...', 'author-profile-blocks' ),
				'success_message'     => __( 'Author metadata updated successfully.', 'author-profile-blocks' ),
				'execute_callback'    => array( self::class, 'update_author_meta_ability' ),
				'input_schema'        => array(
					'type'                  => 'object',
					'properties'            => array(
						'author_id'  => array(
							'type'        => 'integer',
							'description' => __( 'The WordPress user ID to update.', 'author-profile-blocks' ),
							'required'    => true,
						),
						'meta_key'   => array(
							'type'        => 'string',
							'description' => __( 'The metadata key to update.', 'author-profile-blocks' ),
							'enum'        => array( 'apbl_author_description', 'apbl_author_position', 'apbl_social_profiles' ),
							'required'    => true,
						),
						'meta_value' => array(
							'type'        => array( 'string', 'object' ),
							'description' => __( 'The new value for the metadata.', 'author-profile-blocks' ),
							'required'    => true,
						),
					),
					'additional_properties' => false,
				),
				'output_schema'       => array(
					'type'        => 'boolean',
					'description' => __( 'Whether the update was successful.', 'author-profile-blocks' ),
				),
				'permission_callback' => function ( $params ) {
					// Only allow updating your own metadata or if you have edit_users capability
					return current_user_can( 'edit_users' ) || ( get_current_user_id() === $params['author_id'] && current_user_can( 'edit_posts' ) );
				},
			)
		);
	}

	/**
	 * Execute the get-author-data ability
	 *
	 * @param array $params Input parameters.
	 *
	 * @return array|WP_Error Author data.
	 */
	public static function get_author_data_ability( array $params ) {
		$author_id    = $params['author_id'];
		$include_meta = $params['include_meta'] ?? true;

		$author_data = author_profile_blocks()->get_author_data( $author_id );

		if ( ! $author_data ) {
			return new WP_Error( 'author_not_found', __( 'Author not found.', 'author-profile-blocks' ) );
		}

		// Remove sensitive data if not including meta
		if ( ! $include_meta ) {
			unset( $author_data['email'], $author_data['social'] );
		}

		return $author_data;
	}

	/**
	 * Execute the filter-authors ability
	 *
	 * @param array $params Input parameters.
	 *
	 * @return array Filtered authors list.
	 */
	public static function filter_authors_ability( array $params ): array {
		$roles = array();
		if ( ! empty( $params['role'] ) ) {
			$roles = array( $params['role'] );
		}

		$args = array();
		if ( ! empty( $params['capability'] ) ) {
			$args['capability'] = $params['capability'];
		}

		$authors = author_profile_blocks()->get_authors( $roles, $args );

		// Filter by has_posts if specified
		if ( ! empty( $params['has_posts'] ) ) {
			$authors = array_filter(
				$authors,
				static function ( $author ) {
					return count_user_posts( $author['id'], 'post', true ) > 0;
				}
			);
		}

		// Apply limit
		$limit = $params['limit'] ?? 10;
		if ( count( $authors ) > $limit ) {
			$authors = array_slice( $authors, 0, $limit );
		}

		// Simplify the output for AI consumption
		return array_map(
			static fn( $author ) => array(
				'id'     => $author['id'],
				'name'   => $author['title'],
				'email'  => $author['email'],
				'role'   => $author['role'],
				'avatar' => $author['image'],
			),
			$authors
		);
	}

	/**
	 * Execute the render-author-profile ability
	 *
	 * @param array $params Input parameters.
	 *
	 * @return string HTML output.
	 */
	public static function render_author_profile_ability( array $params ): string {
		$author_id = $params['author_id'];

		// Create mock attributes based on input
		$attributes = array(
			'authorId'        => $author_id,
			'showImage'       => $params['show_image'] ?? true,
			'showEmail'       => false, // Don't show email for privacy
			'showDescription' => $params['show_description'] ?? true,
			'showSocial'      => $params['show_social'] ?? true,
			'layoutPreset'    => $params['layout'] ?? 'default',
		);

		// Use the Author_Profile_Block to render
		$block = new Author_Profile_Block();
		// Create a minimal WP_Block object for the render callback
		$wp_block = new \WP_Block( array( 'blockName' => 'author-profile-blocks/author-profile' ) );
		return $block->render_callback( $attributes, '', $wp_block );
	}

	/**
	 * Execute the update-author-meta ability
	 *
	 * @param array $params Input parameters.
	 *
	 * @return bool|WP_Error Success status.
	 */
	public static function update_author_meta_ability( array $params ) {
		$author_id  = $params['author_id'];
		$meta_key   = $params['meta_key'];
		$meta_value = $params['meta_value'];

		// Validate meta key
		$allowed_keys = array( 'apbl_author_description', 'apbl_author_position', 'apbl_social_profiles' );
		if ( ! in_array( $meta_key, $allowed_keys, true ) ) {
			return new WP_Error( 'invalid_meta_key', __( 'Invalid metadata key.', 'author-profile-blocks' ) );
		}

		// For social profiles, ensure it's an array
		if ( 'apbl_social_profiles' === $meta_key && ! is_array( $meta_value ) ) {
			return new WP_Error( 'invalid_social_data', __( 'Social profiles must be an array.', 'author-profile-blocks' ) );
		}

		// Update the metadata
		$result = author_profile_blocks()->get_user_meta_provider()->update_meta( $author_id, $meta_key, $meta_value );

		return (bool) $result;
	}
}
