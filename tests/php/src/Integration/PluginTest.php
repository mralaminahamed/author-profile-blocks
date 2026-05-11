<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration;

use Author_Profile_Blocks;
use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use AuthorProfileBlocks\Blocks\Author_Grid_Block;
use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Blocks\Author_Carousel_Block;
use AuthorProfileBlocks\Core\UserMetaProvider;
use AuthorProfileBlocks\Services\Author_Profile_Service;

/**
 * Integration tests for the main plugin class.
 */
class PluginTest extends IntegrationTestCase {

	public function test_singleton_returns_same_instance(): void {
		$first  = \author_profile_blocks();
		$second = Author_Profile_Blocks::get_instance();

		$this->assertSame( $first, $second );
		$this->assertInstanceOf( Author_Profile_Blocks::class, $first );
	}

	public function test_constants_defined(): void {
		$this->assertTrue( defined( 'APBL_VERSION' ) );
		$this->assertTrue( defined( 'APBL_PLUGIN_FILE' ) );
		$this->assertTrue( defined( 'APBL_PLUGIN_PATH' ) );
		$this->assertTrue( defined( 'APBL_PLUGIN_URL' ) );
		$this->assertSame( '1.0.2', APBL_VERSION );
	}

	public function test_register_blocks_registers_four_blocks(): void {
		$blocks = \author_profile_blocks()->get_blocks();
		$names  = array_map( static fn( $b ) => $b->get_block_name(), $blocks );

		// Other tests may re-register; assert each block name is present
		// rather than checking exact count.
		$this->assertContains( 'author-profile', $names );
		$this->assertContains( 'author-grid', $names );
		$this->assertContains( 'author-carousel', $names );
		$this->assertContains( 'author-list', $names );
	}

	public function test_get_block_returns_correct_instance(): void {
		$plugin = \author_profile_blocks();

		$this->assertInstanceOf( Author_Profile_Block::class, $plugin->get_block( 'author-profile' ) );
		$this->assertInstanceOf( Author_Grid_Block::class, $plugin->get_block( 'author-grid' ) );
		$this->assertInstanceOf( Author_List_Block::class, $plugin->get_block( 'author-list' ) );
		$this->assertInstanceOf( Author_Carousel_Block::class, $plugin->get_block( 'author-carousel' ) );
		$this->assertNull( $plugin->get_block( 'nonexistent' ) );
	}

	public function test_blocks_registered_with_wordpress(): void {
		$registry = \WP_Block_Type_Registry::get_instance();

		$this->assertTrue( $registry->is_registered( 'author-profile-blocks/author-profile' ) );
		$this->assertTrue( $registry->is_registered( 'author-profile-blocks/author-grid' ) );
		$this->assertTrue( $registry->is_registered( 'author-profile-blocks/author-list' ) );
		$this->assertTrue( $registry->is_registered( 'author-profile-blocks/author-carousel' ) );
	}

	public function test_user_meta_fields_registered(): void {
		$keys     = array(
			'apbl_author_description',
			'apbl_author_position',
			'apbl_social_profiles',
			'apbl_member_since_label',
		);
		$registry = \get_registered_meta_keys( 'user' );

		foreach ( $keys as $key ) {
			$this->assertArrayHasKey( $key, $registry, "Meta key {$key} should be registered" );
		}
	}

	public function test_get_user_meta_provider_returns_provider(): void {
		$this->assertInstanceOf(
			UserMetaProvider::class,
			\author_profile_blocks()->get_user_meta_provider()
		);
	}

	public function test_get_author_profile_service_returns_service(): void {
		$this->assertInstanceOf(
			Author_Profile_Service::class,
			\author_profile_blocks()->get_author_profile_service()
		);
	}

	public function test_sanitize_social_profiles_strips_unknown_keys(): void {
		$result = \author_profile_blocks()->sanitize_social_profiles(
			array(
				'facebook' => 'https://facebook.com/foo',
				'twitter'  => 'https://twitter.com/foo',
				'evil'     => '<script>',
			)
		);

		$this->assertArrayHasKey( 'facebook', $result );
		$this->assertArrayHasKey( 'twitter', $result );
		$this->assertArrayHasKey( 'linkedin', $result );
		$this->assertArrayHasKey( 'instagram', $result );
		$this->assertArrayHasKey( 'website', $result );
		$this->assertArrayNotHasKey( 'evil', $result );
		$this->assertSame( 'https://facebook.com/foo', $result['facebook'] );
	}

	public function test_sanitize_social_profiles_returns_empty_for_non_array(): void {
		$this->assertSame( array(), \author_profile_blocks()->sanitize_social_profiles( 'not-an-array' ) );
	}

	public function test_get_settings_uses_default_when_unset(): void {
		\delete_option( 'author_profile_blocks_settings' );
		$this->assertSame( array(), \author_profile_blocks()->get_settings() );
	}

	public function test_get_setting_returns_default_for_missing_key(): void {
		\delete_option( 'author_profile_blocks_settings' );
		$this->assertSame( 'fallback', \author_profile_blocks()->get_setting( 'missing', 'fallback' ) );
	}

	public function test_get_settings_returns_stored_option(): void {
		\update_option(
			'author_profile_blocks_settings',
			array( 'enable_blocks' => false, 'cache_duration' => 12 )
		);
		$settings = \author_profile_blocks()->get_settings();
		$this->assertFalse( $settings['enable_blocks'] );
		$this->assertSame( 12, $settings['cache_duration'] );
	}

	public function test_settings_filter_applied(): void {
		\update_option( 'author_profile_blocks_settings', array( 'a' => 1 ) );

		\add_filter(
			'author_profile_blocks_settings',
			static function ( $settings ) {
				$settings['injected'] = 'yes';
				return $settings;
			}
		);

		$this->assertSame( 'yes', \author_profile_blocks()->get_settings()['injected'] );
	}

	public function test_get_author_data_proxies_to_service(): void {
		$user_id = $this->create_full_author();
		$data    = \author_profile_blocks()->get_author_data( $user_id );

		$this->assertIsArray( $data );
		$this->assertSame( $user_id, $data['id'] );
		$this->assertSame( 'Senior Editor', $data['position'] );
	}

	public function test_get_authors_returns_users_filtered_by_role(): void {
		$author_id = $this->create_full_author();
		$results   = \author_profile_blocks()->get_authors( array( 'author' ) );

		$ids = array_map( static fn( $a ) => $a['id'], $results );
		$this->assertContains( $author_id, $ids );
	}

	public function test_locate_template_falls_back_to_plugin_default(): void {
		$path = \author_profile_blocks()->locate_template( 'blocks/author-profile/wrapper.php' );
		$this->assertStringEndsWith( 'templates/blocks/author-profile/wrapper.php', $path );
		$this->assertFileExists( $path );
	}

	public function test_get_template_html_renders_template(): void {
		$user_id = $this->create_full_author();
		$author  = \author_profile_blocks()->get_author_data( $user_id );

		$html = \author_profile_blocks()->get_template_html(
			'blocks/components/author-name.php',
			array(
				'author'     => $author,
				'attributes' => array(),
			)
		);

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( $author['title'], $html );
	}

	public function test_init_registers_activation_and_deactivation_hooks(): void {
		// Activation/deactivation hooks register against the plugin file path.
		$this->assertNotFalse(
			\has_action( 'activate_' . plugin_basename( APBL_PLUGIN_FILE ) )
		);
		$this->assertNotFalse(
			\has_action( 'deactivate_' . plugin_basename( APBL_PLUGIN_FILE ) )
		);
	}

	public function test_activate_creates_default_settings_option(): void {
		\delete_option( 'author_profile_blocks_settings' );
		\delete_option( 'author_profile_blocks_activated' );

		\author_profile_blocks()->activate();

		$this->assertTrue( (bool) \get_option( 'author_profile_blocks_activated' ) );
		$settings = \get_option( 'author_profile_blocks_settings' );
		$this->assertIsArray( $settings );
		$this->assertTrue( $settings['enable_blocks'] );
	}

	public function test_deactivate_clears_transient(): void {
		\set_transient( 'author_profile_blocks_temp_data', 'x', 60 );
		\author_profile_blocks()->deactivate();
		$this->assertFalse( \get_transient( 'author_profile_blocks_temp_data' ) );
	}

	public function test_save_author_profile_fields_updates_meta_with_valid_nonce(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$user_id = $this->create_author();

		$_POST = array(
			'apbl_profile_nonce'      => \wp_create_nonce( 'apbl_save_profile_data' ),
			'apbl_author_position'    => 'Lead Writer',
			'apbl_author_description' => 'Bio text',
			'apbl_member_since_label' => 'Joined',
			'apbl_social_profiles'    => array( 'facebook' => 'https://facebook.com/x' ),
		);

		\author_profile_blocks()->save_author_profile_fields( $user_id );

		$this->assertSame( 'Lead Writer', \get_user_meta( $user_id, 'apbl_author_position', true ) );
		$this->assertSame( 'Joined', \get_user_meta( $user_id, 'apbl_member_since_label', true ) );
		$social = \get_user_meta( $user_id, 'apbl_social_profiles', true );
		$this->assertSame( 'https://facebook.com/x', $social['facebook'] );

		$_POST = array();
	}

	public function test_save_author_profile_fields_rejects_invalid_nonce(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$user_id = $this->create_author();

		$_POST = array(
			'apbl_profile_nonce'   => 'bogus',
			'apbl_author_position' => 'Should Not Save',
		);

		\author_profile_blocks()->save_author_profile_fields( $user_id );

		$this->assertSame( '', \get_user_meta( $user_id, 'apbl_author_position', true ) );

		$_POST = array();
	}
}
