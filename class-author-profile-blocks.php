<?php
/**
 * Main Plugin Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

use AuthorProfileBlocks\Supports\Abilities_Api;
use AuthorProfileBlocks\Supports\FakerPress;
use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Admin\Plugin_Links;
use AuthorProfileBlocks\Admin\Settings;
use AuthorProfileBlocks\Admin\User_Profile;
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
	 * Settings instance
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * User Meta Provider instance.
	 *
	 * @var User_Meta_Provider
	 */
	private User_Meta_Provider $user_meta_provider;

	/**
	 * User Profile instance.
	 *
	 * @var User_Profile
	 */
	private User_Profile $user_profile;

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
		$this->register_blocks();
	}

	/**
	 * Register instances of services and components.
	 *
	 * @return void
	 */
	public function register_services(): void {
		$this->settings               = new Settings();
		$this->user_meta_provider     = new User_Meta_Provider();
		$this->author_profile_service = new Author_Profile_Service( $this->user_meta_provider );
		$this->user_profile           = new User_Profile( $this->user_meta_provider );
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
		$this->get_user_meta_provider()->add_meta_field(
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
		$this->get_user_meta_provider()->add_meta_field(
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
		$this->get_user_meta_provider()->add_meta_field(
			'apbl_social_profiles',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'object',
				'sanitize_callback' => array( $this->user_profile, 'sanitize_social_profiles' ),
				'auth_callback'     => function () {
					return current_user_can( 'edit_users' );
				},
			)
		);
		$this->get_user_meta_provider()->add_meta_field(
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
		$this->get_user_meta_provider()->register_meta_fields();
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
		$this->get_user_meta_provider()->init();
		$this->get_author_profile_service()->init();

		// Initialize blocks.
		$this->initialize_blocks();

		// Register hooks in groups for better organization.
		$this->get_user_profile()->init();

		// Initialize admin components.
		Plugin_Links::init();
		Admin::init();

		Abilities_Api::init();
		FakerPress::init();

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
		$this->get_settings()->init_defaults();

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
	 * Get author data by ID
	 *
	 * @param int $author_id User ID.
	 *
	 * @return array|null Author data or null if not found
	 */
	public function get_author_data( int $author_id ): ?array {
		return $this->get_author_profile_service()->get_author_data( $author_id );
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
		return $this->get_author_profile_service()->get_authors( $roles, $args );
	}

	/**
	 * Get plugin settings instance
	 *
	 * @return Settings
	 */
	public function get_settings(): Settings {
		return $this->settings;
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
	 * Get the user profile manager.
	 *
	 * @return User_Profile The user profile manager instance.
	 */
	public function get_user_profile(): User_Profile {
		return $this->user_profile;
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
