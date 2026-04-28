<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Admin;

use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for the Admin class.
 */
class AdminTest extends IntegrationTestCase {

	private Admin $admin;

	public function set_up(): void {
		parent::set_up();
		// Re-instantiate so settings hooks register fresh.
		$this->admin = new Admin();
	}

	public function test_constructor_registers_admin_hooks(): void {
		$this->assertNotFalse( \has_action( 'admin_menu' ) );
		$this->assertNotFalse( \has_action( 'admin_init' ) );
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
		// Defaults still present for unset keys.
		$this->assertSame( 24, $settings['cache_duration'] );
		$this->assertContains( 'editor', $settings['author_roles'] );
	}

	public function test_sanitize_settings_show_email_truthy_to_one(): void {
		$out = $this->admin->sanitize_settings( array( 'show_email' => '1' ) );
		$this->assertSame( 1, $out['show_email'] );
	}

	public function test_sanitize_settings_show_email_falsy_to_zero(): void {
		$out = $this->admin->sanitize_settings( array( 'show_email' => '' ) );
		$this->assertSame( 0, $out['show_email'] );
	}

	public function test_sanitize_settings_strips_html_from_author_roles(): void {
		$out = $this->admin->sanitize_settings(
			array( 'author_roles' => array( '<script>editor', 'author' ) )
		);

		$this->assertSame( 'editor', $out['author_roles'][0] );
		$this->assertSame( 'author', $out['author_roles'][1] );
	}

	public function test_sanitize_settings_skips_non_array_author_roles(): void {
		$out = $this->admin->sanitize_settings( array( 'author_roles' => 'not-array' ) );
		$this->assertArrayNotHasKey( 'author_roles', $out );
	}

	public function test_register_settings_registers_settings_group(): void {
		$this->admin->register_settings();

		global $wp_settings_sections, $wp_settings_fields;
		$this->assertArrayHasKey( 'author_profile_blocks_settings', $wp_settings_sections );

		$sections = $wp_settings_sections['author_profile_blocks_settings'];
		$this->assertArrayHasKey( 'author_profile_blocks_general', $sections );
		$this->assertArrayHasKey( 'author_profile_blocks_display', $sections );
		$this->assertArrayHasKey( 'author_profile_blocks_performance', $sections );
	}

	public function test_add_menu_pages_registers_options_page(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$this->admin->add_menu_pages();

		global $submenu;
		$this->assertArrayHasKey( 'options-general.php', $submenu );

		$slugs = array_column( $submenu['options-general.php'], 2 );
		$this->assertContains( 'author-profile-blocks', $slugs );
	}

	public function test_enqueue_scripts_loads_styles_only_on_profile_pages(): void {
		$this->admin->enqueue_scripts( 'profile.php' );
		$this->assertTrue( \wp_style_is( 'author-profile-blocks-admin', 'enqueued' ) );

		\wp_dequeue_style( 'author-profile-blocks-admin' );

		$this->admin->enqueue_scripts( 'edit.php' );
		$this->assertFalse( \wp_style_is( 'author-profile-blocks-admin', 'enqueued' ) );
	}
}
