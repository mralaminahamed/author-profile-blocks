<?php
declare(strict_types=1);
/**
 * Admin Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class for managing plugin settings and admin interface.
 */
class Admin {

	public function __construct() {
		$this->init();
	}

	private function init(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
	}

	public function enqueue_scripts( string $hook ): void {
		if ( 'toplevel_page_author-profile-blocks' !== $hook ) {
			return;
		}

		$asset_file = plugin_dir_path( APBL_PLUGIN_FILE ) . 'build/admin/index.asset.php';
		$asset      = file_exists( $asset_file ) ? require $asset_file : array( 'dependencies' => array(), 'version' => APBL_VERSION );

		wp_enqueue_script(
			'apbl-admin',
			plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/admin/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'apbl-admin-style',
			plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/admin/style-index.css',
			array(),
			$asset['version']
		);

		wp_localize_script(
			'apbl-admin',
			'apblAdmin',
			array(
				'restUrl'   => rest_url( 'author-profile-blocks/v1/' ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
				'version'   => APBL_VERSION,
				'wpRoles'   => array_map( fn( $r ) => $r['name'], wp_roles()->roles ),
			)
		);
	}

	public function add_menu_pages(): void {
		add_menu_page(
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			'manage_options',
			'author-profile-blocks',
			array( $this, 'settings_page' ),
			'dashicons-groups',
			58
		);
	}

	public function settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'author-profile-blocks' ) );
		}

		echo '<div id="apbl-admin-root"></div>';
	}

	public static function get_default_settings(): array {
		return array(
			'author_roles'     => array( 'administrator', 'editor', 'author' ),
			'avatar_size'      => 150,
			'social_platforms' => array( 'facebook', 'twitter', 'linkedin', 'instagram' ),
			'show_email'       => 0,
			'cache_duration'   => 24,
		);
	}

	public static function get_settings(): array {
		return wp_parse_args(
			get_option( 'author_profile_blocks_settings', array() ),
			self::get_default_settings()
		);
	}
}
