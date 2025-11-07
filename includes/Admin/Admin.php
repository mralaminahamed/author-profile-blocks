<?php
/**
 * Admin Class
 *
 * @package AuthorProfileBlocks
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
	 * Settings page callback
	 *
	 * @return void
	 */
	public function settings_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Author Profile Blocks Settings', 'author-profile-blocks' ); ?></h1>
			<p><?php esc_html_e( 'Configure the Author Profile Blocks plugin settings.', 'author-profile-blocks' ); ?></p>

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
}