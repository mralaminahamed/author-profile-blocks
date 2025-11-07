<?php

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Menu Class
 */
class Menu {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
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