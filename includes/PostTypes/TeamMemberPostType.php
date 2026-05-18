<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Team Member Post Type class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\PostTypes;

use AuthorProfileBlocks\Core\Registerable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the apbl_team_member custom post type with position and social profile meta.
 */
class TeamMemberPostType implements Registerable {

	const POST_TYPE = 'apbl_team_member';

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void {
		$this->init();
	}

	/**
	 * Initialize the post type registration.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_meta_fields' ) );
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'         => __( 'Team Members', 'author-profile-blocks' ),
					'singular_name' => __( 'Team Member', 'author-profile-blocks' ),
					'add_new_item' => __( 'Add New Team Member', 'author-profile-blocks' ),
					'edit_item'    => __( 'Edit Team Member', 'author-profile-blocks' ),
				),
				'public'       => true,
				'show_ui'      => true,
				'show_in_rest' => true,
				'rest_base'    => 'apbl-team-members',
				'menu_icon'    => 'dashicons-groups',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'menu_order' ),
				'has_archive'  => false,
				'rewrite'      => array( 'slug' => 'team-member' ),
				'taxonomies'   => array( 'apbl_department' ),
			)
		);
	}

	/**
	 * Register post meta fields.
	 *
	 * @return void
	 */
	public function register_meta_fields(): void {
		register_post_meta(
			self::POST_TYPE,
			'apbl_tm_position',
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta(
			self::POST_TYPE,
			'apbl_tm_social_profiles',
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
