<?php
/**
 * Admin Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class for managing plugin settings and admin interface.
 *
 * Handles the admin settings page, enqueues admin scripts/styles,
 * and manages plugin configuration options.
 */
class Admin {

	/**
	 * Initialize admin functionality
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( self::class, 'add_menu_pages' ) );
		add_action( 'admin_init', array( self::class, 'register_settings' ) );
		add_action( 'rest_api_init', array( self::class, 'register_rest_routes' ) );
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public static function enqueue_scripts( string $hook ): void {
		// Only load on user profile pages
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
	 * Add admin menu pages
	 *
	 * @return void
	 */
	public static function add_menu_pages(): void {
		add_options_page(
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			'manage_options',
			'author-profile-blocks',
			array( self::class, 'settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		// Register main settings group
		register_setting(
			'author_profile_blocks_settings',
			'author_profile_blocks_settings',
			array( author_profile_blocks()->settings, 'sanitize' )
		);

		// General Settings Section
		add_settings_section(
			'author_profile_blocks_general',
			__( 'General Settings', 'author-profile-blocks' ),
			array( self::class, 'general_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Author Roles Field
		add_settings_field(
			'author_roles',
			__( 'Author Roles', 'author-profile-blocks' ),
			array( self::class, 'author_roles_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_general'
		);

		// Display Settings Section
		add_settings_section(
			'author_profile_blocks_display',
			__( 'Display Settings', 'author-profile-blocks' ),
			array( self::class, 'display_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Avatar Size Field
		add_settings_field(
			'avatar_size',
			__( 'Avatar Size (pixels)', 'author-profile-blocks' ),
			array( self::class, 'avatar_size_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Social Media Fields
		add_settings_field(
			'social_platforms',
			__( 'Social Media Platforms', 'author-profile-blocks' ),
			array( self::class, 'social_platforms_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Show Email Field
		add_settings_field(
			'show_email',
			__( 'Show Email Addresses', 'author-profile-blocks' ),
			array( self::class, 'show_email_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Performance Settings Section
		add_settings_section(
			'author_profile_blocks_performance',
			__( 'Performance Settings', 'author-profile-blocks' ),
			array( self::class, 'performance_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Cache Duration Field
		add_settings_field(
			'cache_duration',
			__( 'Cache Duration (hours)', 'author-profile-blocks' ),
			array( self::class, 'cache_duration_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_performance'
		);
	}



	/**
	 * General settings section callback
	 *
	 * @return void
	 */
	public static function general_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure general settings for the Author Profile Blocks plugin.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Display settings section callback
	 *
	 * @return void
	 */
	public static function display_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure how author profiles are displayed in blocks.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Performance settings section callback
	 *
	 * @return void
	 */
	public static function performance_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure performance and caching settings.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Author roles field callback
	 *
	 * @return void
	 */
	public static function author_roles_field_callback(): void {
		author_profile_blocks()->get_template( 'admin/fields/author-roles.php' );
	}

	/**
	 * Avatar size field callback
	 *
	 * @return void
	 */
	public static function avatar_size_field_callback(): void {
		author_profile_blocks()->get_template( 'admin/fields/avatar-size.php' );
	}

	/**
	 * Social platforms field callback
	 *
	 * @return void
	 */
	public static function social_platforms_field_callback(): void {
		author_profile_blocks()->get_template( 'admin/fields/social-platforms.php' );
	}

	/**
	 * Show email field callback
	 *
	 * @return void
	 */
	public static function show_email_field_callback(): void {
		author_profile_blocks()->get_template( 'admin/fields/show-email.php' );
	}

	/**
	 * Cache duration field callback
	 *
	 * @return void
	 */
	public static function cache_duration_field_callback(): void {
		author_profile_blocks()->get_template( 'admin/fields/cache-duration.php' );
	}

	/**
	 * Settings page callback
	 *
	 * @return void
	 */
	public static function settings_page(): void {
		// Check if settings were updated using WordPress function
		$settings_updated = wp_unslash( $_GET['settings-updated'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( isset( $_GET['settings-updated'] ) && rest_sanitize_boolean( $settings_updated ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			add_settings_error(
				'author_profile_blocks_settings',
				'settings_updated',
				esc_html__( 'Settings saved successfully.', 'author-profile-blocks' ),
				'success'
			);
		}

		author_profile_blocks()->get_template( 'admin/settings-page.php' );
	}



	/**
	 * Register REST API routes
	 *
	 * @return void
	 */
	public static function register_rest_routes(): void {
		register_rest_route(
			'author-profile-blocks/v1',
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( self::class, 'get_settings_rest' ),
				'permission_callback' => array( self::class, 'settings_rest_permissions_check' ),
				'args'                => array(),
			)
		);
	}

	/**
	 * REST API permission check for settings endpoint
	 *
	 * @return bool Whether the current user can access settings.
	 */
	public static function settings_rest_permissions_check(): bool {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * REST API callback for getting plugin settings
	 *
	 * @return array<string, mixed> Plugin settings.
	 */
	public static function get_settings_rest(): array {
		return author_profile_blocks()->settings->get_for_rest();
	}
}
