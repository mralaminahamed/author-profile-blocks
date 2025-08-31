<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Plugin class
 *
 * @package AuthorProfileBlocks
 */

namespace APBL\AuthorProfileBlocks;

use APBL\AuthorProfileBlocks\Blocks\Block_Registry;
use APBL\AuthorProfileBlocks\Core\Base;
use APBL\AuthorProfileBlocks\Core\User_Meta_Provider;
use APBL\AuthorProfileBlocks\Services\Author_Profile_Service;
use WP_User;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Plugin extends Base {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Block registry instance.
	 *
	 * @var Block_Registry
	 */
	private Block_Registry $block_registry;

	/**
	 * User meta provider instance.
	 *
	 * @var User_Meta_Provider
	 */
	private User_Meta_Provider $user_meta_provider;

	/**
	 * Author profile service instance.
	 *
	 * @var Author_Profile_Service
	 */
	private Author_Profile_Service $author_profile_service;

	/**
	 * Get plugin instance.
	 *
	 * @return Plugin Plugin instance.
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		// Initialize services.
		$this->register_services();

		add_action( 'int', array( $this, 'register_meta_field' ), 0 );
	}

	/**
	 * Register instances of services and components.
	 *
	 * @return void
	 */
	public function register_services(): void {
		$this->user_meta_provider     = new User_Meta_Provider();
		$this->author_profile_service = new Author_Profile_Service( $this->user_meta_provider );
		$this->block_registry         = new Block_Registry();
	}

	/**
	 * Register instances of services and components.
	 *
	 * @return void
	 */
	public function register_meta_field(): void {
		$this->user_meta_provider->add_meta_field(
			'apbl_author_description',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => function () {
					return current_user_can( 'edit_users' );
				},
			)
		);
		$this->user_meta_provider->add_meta_field(
			'apbl_author_position',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_users' );
				},
			)
		);
		$this->user_meta_provider->add_meta_field(
			'apbl_social_profiles',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_social_profiles' ),
				'auth_callback'     => function () {
					return current_user_can( 'edit_users' );
				},
			)
		);
		$this->user_meta_provider->add_meta_field(
			'apbl_member_since_label',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'default'           => esc_html__( 'Member since', 'author-profile-blocks' ),
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_users' );
				},
			)
		);
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize service components.
		$this->user_meta_provider->init();
		$this->author_profile_service->init();
		$this->block_registry->init();

		// Register hooks in groups for better organization.
		$this->register_user_profile_hooks();
		$this->register_admin_hooks();

		// Set initialized state.
		$this->set_initialized();

		/**
		 * Fires after the Author Profile Blocks plugin has been fully initialized.
		 *
		 * This action allows plugins and themes to interact with the Author Profile Blocks
		 * plugin after it has been initialized. The Plugin instance is passed as a parameter,
		 * providing access to all plugin functionality.
		 *
		 * @since 1.0.0
		 *
		 * @param self $plugin The Plugin instance.
		 */
		do_action( 'author_profile_blocks_init', $this );
	}

	/**
	 * Register user profile related hooks.
	 *
	 * @return void
	 */
	private function register_user_profile_hooks(): void {
		// Add user profile fields.
		add_action( 'show_user_profile', array( $this, 'add_author_profile_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_author_profile_fields' ) );

		// Save user profile fields.
		add_action( 'personal_options_update', array( $this, 'save_author_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_author_profile_fields' ) );
	}

	/**
	 * Register admin related hooks.
	 *
	 * @return void
	 */
	private function register_admin_hooks(): void {
		// Add admin styles for the user profile fields.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
	}

	/**
	 * Add author profile fields to user profile.
	 *
	 * @param WP_User $user The user object.
	 *
	 * @return void
	 */
	public function add_author_profile_fields( WP_User $user ): void {
		// Get current values.
		$description        = $this->user_meta_provider->get_meta( $user->ID, 'apbl_author_description', true );
		$position           = $this->user_meta_provider->get_meta( $user->ID, 'apbl_author_position', true );
		$social_profiles    = $this->user_meta_provider->get_meta( $user->ID, 'apbl_social_profiles', true );
		$member_since_label = $this->user_meta_provider->get_meta( $user->ID, 'apbl_member_since_label', true );

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
		?>

		<h2><?php esc_html_e( 'Author Profile Information', 'author-profile-blocks' ); ?></h2>
		<p><?php esc_html_e( 'These fields are used by the Author Profile Blocks plugin to display author information on your site.', 'author-profile-blocks' ); ?></p>

		<table class="form-table" role="presentation">
			<tr class="apbl-meta-field">
				<th><label for="apbl_author_position"><?php esc_html_e( 'Position/Title', 'author-profile-blocks' ); ?></label></th>
				<td>
					<input type="text" name="apbl_author_position" id="apbl_author_position" value="<?php echo esc_attr( $position ); ?>" class="regular-text"/>
					<p class="description"><?php esc_html_e( 'Enter the author\'s position or title (e.g., "Senior Editor", "Lead Developer", etc.)', 'author-profile-blocks' ); ?></p>
				</td>
			</tr>

			<tr class="apbl-meta-field">
				<th><label for="apbl_member_since_label"><?php esc_html_e( 'Member Since Label', 'author-profile-blocks' ); ?></label></th>
				<td>
					<input type="text" name="apbl_member_since_label" id="apbl_member_since_label" value="<?php echo esc_attr( $member_since_label ); ?>" class="regular-text"/>
					<p class="description"><?php esc_html_e( 'Customize the label used for showing registration date (e.g., "Member since", "Joined on", "With us since", etc.)', 'author-profile-blocks' ); ?></p>
				</td>
			</tr>

			<tr class="apbl-meta-field">
				<th><label for="apbl_author_description"><?php esc_html_e( 'Author Description', 'author-profile-blocks' ); ?></label></th>
				<td>
					<?php
					wp_editor(
						$description,
						'apbl_author_description',
						array(
							'media_buttons' => false,
							'textarea_name' => 'apbl_author_description',
							'textarea_rows' => 5,
							'teeny'         => true,
						)
					);
					?>
					<p class="description"><?php esc_html_e( 'Enter a detailed description for this author.', 'author-profile-blocks' ); ?></p>
				</td>
			</tr>

			<tr class="apbl-meta-field">
				<th><label><?php esc_html_e( 'Social Media Profiles', 'author-profile-blocks' ); ?></label></th>
				<td>
					<div class="apbl-social-profiles">
						<p>
							<label for="apbl_social_facebook"><?php esc_html_e( 'Facebook URL', 'author-profile-blocks' ); ?></label><br/>
							<input type="url" name="apbl_social_profiles[facebook]" id="apbl_social_facebook" value="<?php echo esc_url( $social_profiles['facebook'] ?? '' ); ?>" class="regular-text"/>
						</p>
						<p>
							<label for="apbl_social_twitter"><?php esc_html_e( 'Twitter URL', 'author-profile-blocks' ); ?></label><br/>
							<input type="url" name="apbl_social_profiles[twitter]" id="apbl_social_twitter" value="<?php echo esc_url( $social_profiles['twitter'] ?? '' ); ?>" class="regular-text"/>
						</p>
						<p>
							<label for="apbl_social_linkedin"><?php esc_html_e( 'LinkedIn URL', 'author-profile-blocks' ); ?></label><br/>
							<input type="url" name="apbl_social_profiles[linkedin]" id="apbl_social_linkedin" value="<?php echo esc_url( $social_profiles['linkedin'] ?? '' ); ?>" class="regular-text"/>
						</p>
						<p>
							<label for="apbl_social_instagram"><?php esc_html_e( 'Instagram URL', 'author-profile-blocks' ); ?></label><br/>
							<input type="url" name="apbl_social_profiles[instagram]" id="apbl_social_instagram" value="<?php echo esc_url( $social_profiles['instagram'] ?? '' ); ?>" class="regular-text"/>
						</p>
						<p>
							<label for="apbl_social_website"><?php esc_html_e( 'Personal Website', 'author-profile-blocks' ); ?></label><br/>
							<input type="url" name="apbl_social_profiles[website]" id="apbl_social_website" value="<?php echo esc_url( $social_profiles['website'] ?? '' ); ?>" class="regular-text"/>
						</p>
					</div>
				</td>
			</tr>
		</table>
		<?php

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

		// Update description.
		if ( isset( $_POST['apbl_author_description'] ) ) {
			$this->user_meta_provider->update_meta(
				$user_id,
				'apbl_author_description',
				wp_kses_post( wp_unslash( $_POST['apbl_author_description'] ) )
			);
		}

		// Update position/title.
		if ( isset( $_POST['apbl_author_position'] ) ) {
			$this->user_meta_provider->update_meta(
				$user_id,
				'apbl_author_position',
				sanitize_text_field( wp_unslash( $_POST['apbl_author_position'] ) )
			);
		}

		// Update social profiles.
		if ( isset( $_POST['apbl_social_profiles'] ) && is_array( $_POST['apbl_social_profiles'] ) ) {
			$this->user_meta_provider->update_meta(
				$user_id,
				'apbl_social_profiles',
				$this->sanitize_social_profiles( wp_unslash( $_POST['apbl_social_profiles'] ) ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			);
		}

		// Update member since label.
		if ( isset( $_POST['apbl_member_since_label'] ) ) {
			$this->user_meta_provider->update_meta(
				$user_id,
				'apbl_member_since_label',
				sanitize_text_field( wp_unslash( $_POST['apbl_member_since_label'] ) )
			);
		}

		// Clear the author cache.
		$this->author_profile_service->clear_cache( $user_id );

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

	/**
	 * Enqueue admin styles for the user profile fields.
	 *
	 * @param string $hook The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_admin_styles( string $hook ): void {
		if ( 'user-edit.php' === $hook || 'profile.php' === $hook ) {
			wp_enqueue_style(
				'author-profile-blocks-admin',
				plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/admin/styles.css',
				array(),
				APBL_VERSION,
				'all'
			);
		}
	}

	/**
	 * Get author data by ID
	 *
	 * @param int $author_id User ID.
	 *
	 * @return array|null Author data or null if not found
	 */
	public function get_author_data( int $author_id ): ?array {
		return $this->author_profile_service->get_author_data( $author_id );
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
		return $this->author_profile_service->get_authors( $roles, $args );
	}

	/**
	 * Get the block registry.
	 *
	 * @return Block_Registry Block registry instance.
	 */
	public function get_block_registry(): Block_Registry {
		return $this->block_registry;
	}

	/**
	 * Get the user meta provider.
	 *
	 * @return User_Meta_Provider The user meta provider instance.
	 */
	public function get_user_meta_provider(): User_Meta_Provider {
		return $this->user_meta_provider;
	}

	/**
	 * Get the author profile service.
	 *
	 * @return Author_Profile_Service The author profile service instance.
	 */
	public function get_author_profile_service(): Author_Profile_Service {
		return $this->author_profile_service;
	}
}
