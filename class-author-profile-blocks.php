<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Main Plugin Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

use AuthorProfileBlocks\Abilities_Api;
use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Admin\Plugin_Links;
use AuthorProfileBlocks\Blocks\Author_Block_Base;
use AuthorProfileBlocks\Blocks\Author_Carousel_Block;
use AuthorProfileBlocks\Blocks\Author_Grid_Block;
use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use AuthorProfileBlocks\Core\User_Meta_Provider;
use AuthorProfileBlocks\Services\Author_Profile_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class for Author Profile Blocks.
 */
class Author_Profile_Blocks {
	/**
	 * Plugin instance.
	 *
	 * @var Author_Profile_Blocks|null
	 */
	private static ?Author_Profile_Blocks $instance = null;

	/**
	 * List of blocks to register.
	 *
	 * @var Author_Block_Base[]
	 */
	private array $blocks = array();

	/**
	 * User Meta Provider instance.
	 *
	 * @var User_Meta_Provider
	 */
	private User_Meta_Provider $user_meta_provider;

	/**
	 * Author Profile Service instance.
	 *
	 * @var Author_Profile_Service
	 */
	private Author_Profile_Service $author_profile_service;

	/**
	 * Get plugin instance.
	 *
	 * @return Author_Profile_Blocks Plugin instance.
	 */
	public static function get_instance(): Author_Profile_Blocks {
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
	}

	/**
	 * Register instances of services and components.
	 *
	 * @return void
	 */
	public function register_services(): void {
		$this->user_meta_provider     = new User_Meta_Provider();
		$this->author_profile_service = new Author_Profile_Service( $this->user_meta_provider );

		$this->register_blocks();
	}

	/**
	 * Register admin components.
	 *
	 * @return void
	 */
	private function register_admin(): void {
		new Admin();
		new Plugin_Links();
	}

	/**
	 * Register all blocks.
	 *
	 * @return void
	 */
	private function register_blocks(): void {
		// Register all blocks here.
		$this->register_block( new Author_Profile_Block() );
		$this->register_block( new Author_Grid_Block() );
		$this->register_block( new Author_Carousel_Block() );
		$this->register_block( new Author_List_Block() );

		// Allow plugins/themes to register additional blocks.
		do_action( 'author_profile_blocks_register_blocks', $this );
	}

	/**
	 * Initialize all registered blocks.
	 *
	 * @return void
	 */
	private function initialize_blocks(): void {
		// Initialize each block.
		foreach ( $this->blocks as $block ) {
			$block->init();
		}

		// Allow other components to interact with our block registry.
		do_action( 'author_profile_blocks_blocks_registered', $this );
	}

	/**
	 * Register a block instance.
	 *
	 * @param Author_Block_Base $block Block instance.
	 *
	 * @return void
	 */
	public function register_block( Author_Block_Base $block ): void {
		$this->blocks[] = $block;
	}

	/**
	 * Get all registered blocks.
	 *
	 * @return Author_Block_Base[] Array of block instances.
	 */
	public function get_blocks(): array {
		return $this->blocks;
	}

	/**
	 * Get a specific block by name.
	 *
	 * @param string $name Block name.
	 *
	 * @return Author_Block_Base|null Block instance or null if not found.
	 */
	public function get_block( string $name ): ?Author_Block_Base {
		foreach ( $this->blocks as $block ) {
			if ( $block->get_block_name() === $name ) {
				return $block;
			}
		}

		return null;
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

		// Register the meta fields with WordPress.
		$this->user_meta_provider->register_meta_fields();
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register activation/deactivation hooks.
		register_activation_hook( APBL_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( APBL_PLUGIN_FILE, array( $this, 'deactivate' ) );

		// Register early hooks.
		add_action( 'init', array( $this, 'register_meta_field' ), 0 );
		add_action( 'init', array( $this, 'init_components' ) );
	}

	/**
	 * Initialize plugin components.
	 *
	 * @return void
	 */
	public function init_components(): void {
		// Initialize service components first (blocks may depend on them).
		$this->user_meta_provider->init();
		$this->author_profile_service->init();

		// Initialize blocks.
		$this->initialize_blocks();

		// Initialize Abilities API.
		Abilities_Api::init();

		// Initialize admin components.
		if ( is_admin() ) {
			new Plugin_Links();
			new Admin();
		}

		// Register hooks in groups for better organization.
		$this->register_user_profile_hooks();
		$this->register_admin_hooks();
		$this->init_fakerpress_integration();

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
	 * Plugin activation.
	 *
	 * @return void
	 */
	public function activate(): void {
		// Set default options.
		$this->set_default_options();

		// Flush rewrite rules.
		flush_rewrite_rules();

		// Set activation flag.
		update_option( 'author_profile_blocks_activated', true );

		do_action( 'author_profile_blocks_activated' );
	}

	/**
	 * Plugin deactivation.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		// Clear cached data.
		delete_transient( 'author_profile_blocks_temp_data' );

		// Flush rewrite rules.
		flush_rewrite_rules();

		do_action( 'author_profile_blocks_deactivated' );
	}

	/**
	 * Set default plugin options.
	 *
	 * @return void
	 */
	private function set_default_options(): void {
		if ( ! get_option( 'author_profile_blocks_settings' ) ) {
			$default_settings = array(
				'enable_blocks' => true,
			);
			update_option( 'author_profile_blocks_settings', $default_settings );
		}
	}

	/**
	 * Get plugin settings.
	 *
	 * @return array<string, mixed>
	 */
	public function get_settings(): array {
		$settings = get_option( 'author_profile_blocks_settings', array() );

		/**
		 * Filter the plugin settings.
		 *
		 * @param array $settings The plugin settings array.
		 */
		return apply_filters( 'author_profile_blocks_settings', $settings );
	}

	/**
	 * Get specific setting.
	 *
	 * @param string $key           Setting key.
	 * @param mixed  $default_value Default value.
	 *
	 * @return mixed
	 */
	public function get_setting( string $key, $default_value = '' ) {
		$settings = $this->get_settings();
		$value    = $settings[ $key ] ?? $default_value;

		/**
		 * Filter the specific plugin setting value.
		 *
		 * @param mixed  $value         The setting value.
		 * @param string $key           The setting key.
		 * @param mixed  $default_value The default value.
		 */
		return apply_filters( 'author_profile_blocks_setting', $value, $key, $default_value );
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
	 * Initialize FakerPress integration.
	 *
	 * Registers custom meta fields with FakerPress for automatic user generation.
	 *
	 * @return void
	 */
	private function init_fakerpress_integration(): void {
		// Only proceed if FakerPress is active.
		if ( ! class_exists( 'FakerPress\Plugin' ) ) {
			return;
		}

		// Hook into FakerPress to register our custom meta types.
		add_filter( 'fakerpress/fields/meta_types', array( $this, 'register_fakerpress_meta_types' ) );

		// Hook into user generation to set default values for our meta fields.
		add_action( 'fakerpress.module.user.before_save', array( $this, 'set_fakerpress_user_defaults' ), 10, 2 );

		// Hook into meta value generation for our specific fields.
		add_filter( 'fakerpress.module.meta.value', array( $this, 'generate_fakerpress_meta_value' ), 10, 3 );
	}

	/**
	 * Register custom meta types with FakerPress.
	 *
	 * @param mixed $meta_types Existing meta types (array or stdClass).
	 *
	 * @return mixed Modified meta types.
	 */
	public function register_fakerpress_meta_types( $meta_types ) {
		$meta_types->apbl_author_description = (object) array(
			'value'       => '',
			'text'        => __( 'Author Description', 'author-profile-blocks' ),
			'description' => __( 'A detailed description for the author profile.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_author_position    = (object) array(
			'value'       => '',
			'text'        => __( 'Author Position/Title', 'author-profile-blocks' ),
			'description' => __( 'The author\'s position or job title.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_social_profiles    = (object) array(
			'value'       => '',
			'text'        => __( 'Social Media Profiles', 'author-profile-blocks' ),
			'description' => __( 'Social media profile URLs for the author.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_member_since_label = (object) array(
			'value'       => '',
			'text'        => __( 'Member Since Label', 'author-profile-blocks' ),
			'description' => __( 'Custom label for the member since date.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);

		return $meta_types;
	}

	/**
	 * Set default values for Author Profile Blocks meta fields during FakerPress user generation.
	 *
	 * @param WP_User $user     The user object being generated.
	 * @param array   $_user_data The user data array.
	 *
	 * @return void
	 */
	public function set_fakerpress_user_defaults( WP_User $user, array $_user_data ): void {
		// Set default member since label if not already set.
		if ( ! metadata_exists( 'user', $user->ID, 'apbl_member_since_label' ) ) {
			update_user_meta( $user->ID, 'apbl_member_since_label', __( 'Member since', 'author-profile-blocks' ) );
		}

		// Initialize empty social profiles array if not already set.
		if ( ! metadata_exists( 'user', $user->ID, 'apbl_social_profiles' ) ) {
			update_user_meta(
				$user->ID,
				'apbl_social_profiles',
				array(
					'facebook'  => '',
					'twitter'   => '',
					'linkedin'  => '',
					'instagram' => '',
					'website'   => '',
				)
			);
		}
	}

	/**
	 * Generate appropriate values for Author Profile Blocks meta fields.
	 *
	 * @param mixed  $value     The current meta value.
	 * @param string $meta_key  The meta key.
	 * @param array  $_field    The field configuration.
	 *
	 * @return mixed The generated meta value.
	 */
	public function generate_fakerpress_meta_value( $value, string $meta_key, array $_field ) {
		switch ( $meta_key ) {
			case 'apbl_author_description':
				if ( empty( $value ) ) {
					$value = $this->generate_author_description();
				}
				break;

			case 'apbl_author_position':
				if ( empty( $value ) ) {
					$value = $this->generate_author_position();
				}
				break;

			case 'apbl_social_profiles':
				if ( empty( $value ) || ! is_array( $value ) ) {
					$value = $this->generate_social_profiles();
				}
				break;

			case 'apbl_member_since_label':
				if ( empty( $value ) ) {
					$value = $this->generate_member_since_label();
				}
				break;
		}

		return $value;
	}

	/**
	 * Generate a realistic author description.
	 *
	 * @return string The generated author description.
	 */
	private function generate_author_description(): string {
		$descriptions = array(
			__( 'Passionate content creator with over 5 years of experience in digital marketing and creative writing. Specializes in crafting compelling narratives that engage audiences and drive results.', 'author-profile-blocks' ),
			__( 'Experienced developer and tech enthusiast who loves building innovative solutions. Always staying up-to-date with the latest technologies and sharing knowledge with the community.', 'author-profile-blocks' ),
			__( 'Creative designer with an eye for detail and a passion for user experience. Combines artistic vision with technical expertise to create beautiful, functional designs.', 'author-profile-blocks' ),
			__( 'Dedicated educator and lifelong learner committed to sharing knowledge and helping others grow. Believes in the power of education to transform lives and communities.', 'author-profile-blocks' ),
			__( 'Strategic thinker and business professional with a track record of driving growth and innovation. Passionate about helping organizations achieve their goals through smart strategies.', 'author-profile-blocks' ),
		);

		return $descriptions[ array_rand( $descriptions ) ];
	}

	/**
	 * Generate a realistic author position/title.
	 *
	 * @return string The generated author position.
	 */
	private function generate_author_position(): string {
		$positions = array(
			__( 'Senior Content Writer', 'author-profile-blocks' ),
			__( 'Lead Developer', 'author-profile-blocks' ),
			__( 'Creative Director', 'author-profile-blocks' ),
			__( 'Marketing Manager', 'author-profile-blocks' ),
			__( 'Product Manager', 'author-profile-blocks' ),
			__( 'UX Designer', 'author-profile-blocks' ),
			__( 'Technical Writer', 'author-profile-blocks' ),
			__( 'Community Manager', 'author-profile-blocks' ),
			__( 'Business Analyst', 'author-profile-blocks' ),
			__( 'Project Coordinator', 'author-profile-blocks' ),
		);

		return $positions[ array_rand( $positions ) ];
	}

	/**
	 * Generate realistic social media profiles.
	 *
	 * @return array The generated social profiles array.
	 */
	private function generate_social_profiles(): array {
		$profiles = array(
			'facebook'  => '',
			'twitter'   => '',
			'linkedin'  => '',
			'instagram' => '',
			'website'   => '',
		);

		// Randomly populate some social profiles (70% chance for each).
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['facebook'] = 'https://facebook.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['twitter'] = 'https://twitter.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['linkedin'] = 'https://linkedin.com/in/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['instagram'] = 'https://instagram.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['website'] = 'https://example' . wp_rand( 1000, 9999 ) . '.com';
		}

		return $profiles;
	}

	/**
	 * Generate a member since label.
	 *
	 * @return string The generated member since label.
	 */
	private function generate_member_since_label(): string {
		$labels = array(
			__( 'Member since', 'author-profile-blocks' ),
			__( 'Joined', 'author-profile-blocks' ),
			__( 'With us since', 'author-profile-blocks' ),
			__( 'Active since', 'author-profile-blocks' ),
		);

		return $labels[ array_rand( $labels ) ];
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
	 * @return self Block registry instance.
	 */
	public function get_block_registry(): self {
		return $this;
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

	/**
	 * Get template part (for templates in loop).
	 *
	 * @param string $slug Template slug.
	 * @param string $name Template name (optional).
	 *
	 * @return void
	 */
	public function get_template_part( string $slug, string $name = '' ): void {
		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/author-profile-blocks/slug-name.php.
		if ( $name ) {
			$template = $this->locate_template( "{$slug}-{$name}.php" );
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/author-profile-blocks/slug.php.
		if ( ! $template ) {
			$template = $this->locate_template( "{$slug}.php" );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'author_profile_blocks_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}

	/**
	 * Get other templates (e.g. product attributes) passing attributes and including the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return void
	 */
	public function get_template( string $template_name, array $args = array(), string $template_path = '', string $default_path = '' ): void {
		$template = $this->locate_template( $template_name, $template_path, $default_path );

		// Allow 3rd party plugin filter template file from their plugin.
		$filter_template = apply_filters( 'author_profile_blocks_get_template', $template, $template_name, $args, $template_path, $default_path );

		if ( $filter_template !== $template ) {
			$template = $filter_template;
		}

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}

		$action_args = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'located'       => $template,
			'args'          => $args,
		);

		if ( ! empty( $template ) ) {
			do_action( 'author_profile_blocks_before_template_part', $action_args );
			include $template;
			do_action( 'author_profile_blocks_after_template_part', $action_args );
		}
	}

	/**
	 * Like wc_get_template, but returns the HTML instead of outputting.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public function get_template_html( string $template_name, array $args = array(), string $template_path = '', string $default_path = '' ): string {
		ob_start();
		$this->get_template( $template_name, $args, $template_path, $default_path );
		return ob_get_clean();
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/author-profile-blocks/$template_name
	 * $default_path/$template_name
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public function locate_template( string $template_name, string $template_path = '', string $default_path = '' ): string {
		if ( ! $template_path ) {
			$template_path = 'author-profile-blocks/';
		}

		if ( ! $default_path ) {
			$default_path = plugin_dir_path( APBL_PLUGIN_FILE ) . 'templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'author_profile_blocks_locate_template', $template, $template_name, $template_path );
	}
}
