<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Admin;

use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for the Admin class (React SPA + REST API).
 */
class AdminTest extends IntegrationTestCase {

	private Admin $admin;

	public function set_up(): void {
		parent::set_up();
		$this->admin = new Admin();
	}

	public function test_constructor_registers_admin_hooks(): void {
		$this->assertNotFalse( \has_action( 'admin_menu' ) );
		$this->assertNotFalse( \has_action( 'admin_enqueue_scripts' ) );
	}

	public function test_default_settings_have_expected_shape(): void {
		$defaults = Admin::get_default_settings();

		$this->assertSame( array( 'administrator', 'editor', 'author' ), $defaults['author_roles'] );
		$this->assertSame( 150, $defaults['avatar_size'] );
		$this->assertSame(
			array( 'facebook', 'twitter', 'linkedin', 'instagram' ),
			$defaults['social_platforms']
		);
		$this->assertSame( 0, $defaults['show_email'] );
		$this->assertSame( 24, $defaults['cache_duration'] );
	}

	public function test_get_settings_merges_stored_with_defaults(): void {
		\update_option(
			'author_profile_blocks_settings',
			array( 'avatar_size' => 200 )
		);

		$settings = Admin::get_settings();

		$this->assertSame( 200, $settings['avatar_size'] );
		$this->assertSame( 24, $settings['cache_duration'] );
		$this->assertContains( 'editor', $settings['author_roles'] );
	}

	public function test_add_menu_pages_registers_toplevel_page(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$this->admin->add_menu_pages();

		global $menu;
		$slugs = array_column( $menu, 2 );
		$this->assertContains( 'author-profile-blocks', $slugs );
	}

	public function test_enqueue_scripts_skips_unrelated_admin_pages(): void {
		$this->admin->enqueue_scripts( 'profile.php' );
		$this->assertFalse( \wp_script_is( 'apbl-admin', 'enqueued' ) );

		$this->admin->enqueue_scripts( 'edit.php' );
		$this->assertFalse( \wp_script_is( 'apbl-admin', 'enqueued' ) );
	}

	public function test_enqueue_scripts_loads_assets_on_plugin_page(): void {
		$this->admin->enqueue_scripts( 'toplevel_page_author-profile-blocks' );
		$this->assertTrue( \wp_script_is( 'apbl-admin', 'enqueued' ) );
		$this->assertTrue( \wp_style_is( 'apbl-admin-style', 'enqueued' ) );
	}
}
