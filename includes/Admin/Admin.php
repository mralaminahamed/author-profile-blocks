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
 * Admin Class
 */
class Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize admin functionality
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_scripts( string $hook ): void {
		// Only load on user profile pages
		if ( 'user-edit.php' === $hook || 'profile.php' === $hook ) {
			wp_enqueue_style(
				'author-profile-blocks-admin',
				APBL_PLUGIN_URL . 'build/admin/styles.css',
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
	public function add_menu_pages(): void {
		add_options_page(
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			'manage_options',
			'author-profile-blocks',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function register_settings(): void {
		// Register main settings group
		register_setting(
			'author_profile_blocks_settings',
			'author_profile_blocks_settings',
			array( $this, 'sanitize_settings' )
		);

		// General Settings Section
		add_settings_section(
			'author_profile_blocks_general',
			__( 'General Settings', 'author-profile-blocks' ),
			array( $this, 'general_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Author Roles Field
		add_settings_field(
			'author_roles',
			__( 'Author Roles', 'author-profile-blocks' ),
			array( $this, 'author_roles_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_general'
		);

		// Display Settings Section
		add_settings_section(
			'author_profile_blocks_display',
			__( 'Display Settings', 'author-profile-blocks' ),
			array( $this, 'display_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Avatar Size Field
		add_settings_field(
			'avatar_size',
			__( 'Avatar Size (pixels)', 'author-profile-blocks' ),
			array( $this, 'avatar_size_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Social Media Fields
		add_settings_field(
			'social_platforms',
			__( 'Social Media Platforms', 'author-profile-blocks' ),
			array( $this, 'social_platforms_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Show Email Field
		add_settings_field(
			'show_email',
			__( 'Show Email Addresses', 'author-profile-blocks' ),
			array( $this, 'show_email_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_display'
		);

		// Performance Settings Section
		add_settings_section(
			'author_profile_blocks_performance',
			__( 'Performance Settings', 'author-profile-blocks' ),
			array( $this, 'performance_settings_section_callback' ),
			'author_profile_blocks_settings'
		);

		// Cache Duration Field
		add_settings_field(
			'cache_duration',
			__( 'Cache Duration (hours)', 'author-profile-blocks' ),
			array( $this, 'cache_duration_field_callback' ),
			'author_profile_blocks_settings',
			'author_profile_blocks_performance'
		);
	}

	/**
	 * Sanitize settings before saving
	 *
	 * @param array $input Raw input data.
	 * @return array Sanitized data.
	 */
	public function sanitize_settings( array $input ): array {
		$sanitized = array();

		// Sanitize author roles using WordPress functions
		if ( isset( $input['author_roles'] ) && is_array( $input['author_roles'] ) ) {
			$sanitized['author_roles'] = array_map( 'sanitize_text_field', wp_unslash( $input['author_roles'] ) );
		}

		// Sanitize avatar size
		if ( isset( $input['avatar_size'] ) ) {
			$sanitized['avatar_size'] = absint( wp_unslash( $input['avatar_size'] ) );
			// Ensure reasonable bounds
			$sanitized['avatar_size'] = wp_clamp( $sanitized['avatar_size'], 32, 512 );
		}

		// Sanitize social platforms
		if ( isset( $input['social_platforms'] ) && is_array( $input['social_platforms'] ) ) {
			$sanitized['social_platforms'] = array_map( 'sanitize_text_field', wp_unslash( $input['social_platforms'] ) );
		}

		// Sanitize show email using WordPress boolean function
		$sanitized['show_email'] = rest_sanitize_boolean( wp_unslash( $input['show_email'] ?? false ) ) ? 1 : 0;

		// Sanitize cache duration
		if ( isset( $input['cache_duration'] ) ) {
			$sanitized['cache_duration'] = absint( wp_unslash( $input['cache_duration'] ) );
			// Ensure reasonable bounds (1 hour to 1 week)
			$sanitized['cache_duration'] = wp_clamp( $sanitized['cache_duration'], 1, 168 );
		}

		return $sanitized;
	}

	/**
	 * General settings section callback
	 *
	 * @return void
	 */
	public function general_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure general settings for the Author Profile Blocks plugin.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Display settings section callback
	 *
	 * @return void
	 */
	public function display_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure how author profiles are displayed in blocks.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Performance settings section callback
	 *
	 * @return void
	 */
	public function performance_settings_section_callback(): void {
		echo '<p>' . esc_html__( 'Configure performance and caching settings.', 'author-profile-blocks' ) . '</p>';
	}

	/**
	 * Author roles field callback
	 *
	 * @return void
	 */
	public function author_roles_field_callback(): void {
		$this->load_admin_template( 'fields/author-roles.php' );
	}

	/**
	 * Avatar size field callback
	 *
	 * @return void
	 */
	public function avatar_size_field_callback(): void {
		$this->load_admin_template( 'fields/avatar-size.php' );
	}

	/**
	 * Social platforms field callback
	 *
	 * @return void
	 */
	public function social_platforms_field_callback(): void {
		$this->load_admin_template( 'fields/social-platforms.php' );
	}

	/**
	 * Show email field callback
	 *
	 * @return void
	 */
	public function show_email_field_callback(): void {
		$this->load_admin_template( 'fields/show-email.php' );
	}

	/**
	 * Cache duration field callback
	 *
	 * @return void
	 */
	public function cache_duration_field_callback(): void {
		$this->load_admin_template( 'fields/cache-duration.php' );
	}

	/**
	 * Settings page callback
	 *
	 * @return void
	 */
	public function settings_page(): void {
		// Check if settings were updated using WordPress function
		if ( isset( $_GET['settings-updated'] ) && rest_sanitize_boolean( wp_unslash( $_GET['settings-updated'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_settings_error(
				'author_profile_blocks_settings',
				'settings_updated',
				esc_html__( 'Settings saved successfully.', 'author-profile-blocks' ),
				'success'
			);
		}

		$this->load_admin_template( 'settings-page.php' );
	}

	/**
	 * Load admin template
	 *
	 * @param string $template Template file name.
	 * @param array  $data     Optional data to pass to template.
	 * @return void
	 */
	private function load_admin_template( string $template, array $data = array() ): void {
		$template_path = plugin_dir_path( APBL_PLUGIN_FILE ) . 'templates/admin/' . ltrim( $template, '/' );

		if ( file_exists( $template_path ) && is_readable( $template_path ) ) {
			// Use WordPress load_template function for better security
			load_template( $template_path, false, $data );
		} else {
			// Fallback for settings page
			if ( 'settings-page.php' === $template ) {
				$this->render_settings_fallback();
			}
		}
	}

	/**
	 * Render settings page fallback
	 *
	 * @return void
	 */
	private function render_settings_fallback(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Author Profile Blocks Settings', 'author-profile-blocks' ); ?></h1>
			<p><?php esc_html_e( 'Configure the Author Profile Blocks plugin settings.', 'author-profile-blocks' ); ?></p>

			<?php settings_errors( 'author_profile_blocks_settings' ); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'author_profile_blocks_settings' );
				do_settings_sections( 'author_profile_blocks_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get default settings
	 *
	 * @return array Default settings array.
	 */
	public static function get_default_settings(): array {
		return array(
			'author_roles'    => array( 'administrator', 'editor', 'author' ),
			'avatar_size'     => 150,
			'social_platforms' => array( 'facebook', 'twitter', 'linkedin', 'instagram' ),
			'show_email'      => 0,
			'cache_duration'  => 24,
		);
	}

	/**
	 * Get plugin settings with defaults
	 *
	 * @return array Plugin settings merged with defaults.
	 */
	public static function get_settings(): array {
		return wp_parse_args(
			get_option( 'author_profile_blocks_settings', array() ),
			self::get_default_settings()
		);
	}
}
