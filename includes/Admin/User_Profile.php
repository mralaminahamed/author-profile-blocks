<?php
/**
 * User Profile Class
 *
 * @package AuthorProfileBlocks\Admin
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Admin;

use AuthorProfileBlocks\Core\User_Meta_Provider;
use WP_User;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User Profile class for managing user profile fields and meta.
 *
 * Handles the addition, display, and saving of author profile fields
 * in the WordPress user profile pages.
 */
class User_Profile {

	/**
	 * Register user profile related hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		// Add user profile fields.
		add_action( 'show_user_profile', array( $this, 'add_author_profile_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_author_profile_fields' ) );

		// Save user profile fields.
		add_action( 'personal_options_update', array( $this, 'save_author_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_author_profile_fields' ) );
	}

	/**
	 * Add author profile fields to user profile.
	 *
	 * @param WP_User $user The user object.
	 *
	 * @return void
	 */
	public function add_author_profile_fields( WP_User $user ): void {
		// Get user meta provider.
		$user_meta_provider = author_profile_blocks()->get_user_meta_provider();

		// Get current values.
		$description        = $user_meta_provider->get_meta( $user->ID, 'apbl_author_description', true );
		$position           = $user_meta_provider->get_meta( $user->ID, 'apbl_author_position', true );
		$social_profiles    = $user_meta_provider->get_meta( $user->ID, 'apbl_social_profiles', true );
		$member_since_label = $user_meta_provider->get_meta( $user->ID, 'apbl_member_since_label', true );

		// Use default if empty.
		if ( empty( $member_since_label ) ) {
			$member_since_label = __( 'Member since', 'author-profile-blocks' );
		}

		if ( ! is_array( $social_profiles ) ) {
			$social_profiles = array(
				'facebook'  => '',
				'twitter'   => '',
				'linkedin'  => '',
				'instagram' => '',
				'website'   => '',
			);
		}

		wp_nonce_field( 'apbl_save_profile_data', 'apbl_profile_nonce' );

		// Load the user profile fields template.
		author_profile_blocks()->get_template(
			'admin/user-profile-fields.php',
			compact( 'description', 'position', 'social_profiles', 'member_since_label' )
		);

		/**
		 * Fires after displaying the built-in author profile fields.
		 *
		 * This action allows plugins and themes to add their own custom fields
		 * to the user profile page within the Author Profile Blocks section.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_User $user The user object for the user being edited.
		 */
		do_action( 'author_profile_blocks_profile_fields', $user );
	}

	/**
	 * Save author profile fields.
	 *
	 * @param int $user_id The ID of the user being saved.
	 *
	 * @return void
	 */
	public function save_author_profile_fields( int $user_id ): void {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		// Verify nonce before processing form data.
		if ( ! isset( $_POST['apbl_profile_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['apbl_profile_nonce'] ), 'apbl_save_profile_data' ) ) {
			return;
		}

		// Get user meta provider.
		$user_meta_provider = author_profile_blocks()->get_user_meta_provider();

		// Update description.
		if ( isset( $_POST['apbl_author_description'] ) ) {
			$user_meta_provider->update_meta(
				$user_id,
				'apbl_author_description',
				wp_kses_post( wp_unslash( $_POST['apbl_author_description'] ) )
			);
		}

		// Update position/title.
		if ( isset( $_POST['apbl_author_position'] ) ) {
			$user_meta_provider->update_meta(
				$user_id,
				'apbl_author_position',
				sanitize_text_field( wp_unslash( $_POST['apbl_author_position'] ) )
			);
		}

		// Update social profiles.
		if ( isset( $_POST['apbl_social_profiles'] ) && is_array( $_POST['apbl_social_profiles'] ) ) {
			$user_meta_provider->update_meta(
				$user_id,
				'apbl_social_profiles',
				$this->sanitize_social_profiles( wp_unslash( $_POST['apbl_social_profiles'] ) ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			);
		}

		// Update member since label.
		if ( isset( $_POST['apbl_member_since_label'] ) ) {
			$user_meta_provider->update_meta(
				$user_id,
				'apbl_member_since_label',
				sanitize_text_field( wp_unslash( $_POST['apbl_member_since_label'] ) )
			);
		}

		// Clear the author cache.
		author_profile_blocks()->get_author_profile_service()->clear_cache( $user_id );

		/**
		 * Fires after the author profile fields are saved.
		 *
		 * This action allows plugins and themes to save additional author profile fields
		 * or perform operations after the built-in fields have been saved.
		 *
		 * @since 1.0.0
		 *
		 * @param int   $user_id The ID of the user being saved.
		 * @param array $_POST   The raw POST data containing all submitted form values.
		 */
		do_action( 'author_profile_blocks_save_profile_fields', $user_id, $_POST );
	}

	/**
	 * Sanitize social profile URLs.
	 *
	 * @param array $profiles The social profile URLs.
	 *
	 * @return array The sanitized social profile URLs.
	 */
	public function sanitize_social_profiles( $profiles ): array {
		$sanitized = array();

		if ( ! is_array( $profiles ) ) {
			return array();
		}

		$allowed_profiles = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'website' );

		foreach ( $allowed_profiles as $profile ) {
			$sanitized[ $profile ] = isset( $profiles[ $profile ] ) ? esc_url_raw( $profiles[ $profile ] ) : '';
		}

		return $sanitized;
	}
}
