<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Services;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for Author_Profile_Service.
 */
class AuthorProfileServiceTest extends IntegrationTestCase {

	private function service() {
		return \author_profile_blocks()->get_author_profile_service();
	}

	public function test_get_author_data_returns_null_for_missing_user(): void {
		$this->assertNull( $this->service()->get_author_data( 999999 ) );
	}

	public function test_get_author_data_returns_complete_array(): void {
		$user_id = $this->create_full_author();
		$data    = $this->service()->get_author_data( $user_id );

		$this->assertIsArray( $data );

		$expected_keys = array(
			'id',
			'title',
			'email',
			'description',
			'position',
			'social',
			'image',
			'registered_date',
			'member_since_label',
			'role',
		);
		foreach ( $expected_keys as $k ) {
			$this->assertArrayHasKey( $k, $data, "Missing key: {$k}" );
		}

		$this->assertSame( $user_id, $data['id'] );
		$this->assertSame( 'Senior Editor', $data['position'] );
		$this->assertStringContainsString( 'Long-form author bio', $data['description'] );
		$this->assertSame( 'Writing since', $data['member_since_label'] );
		$this->assertSame( 'author', $data['role'] );
		$this->assertIsArray( $data['social'] );
		$this->assertSame( 'https://twitter.com/test', $data['social']['twitter'] );
	}

	public function test_get_author_data_uses_default_member_since_label_when_empty(): void {
		$user_id = $this->create_author();
		$data    = $this->service()->get_author_data( $user_id );

		$this->assertSame( 'Member since', $data['member_since_label'] );
	}

	public function test_get_author_data_caches_result_in_memory(): void {
		$user_id = $this->create_full_author();

		$first  = $this->service()->get_author_data( $user_id );

		// Mutate underlying meta — cached call should still return first value.
		\update_user_meta( $user_id, 'apbl_author_position', 'Different' );

		$second = $this->service()->get_author_data( $user_id );

		$this->assertSame( $first['position'], $second['position'] );
		$this->assertSame( 'Senior Editor', $second['position'] );
	}

	public function test_clear_cache_for_specific_user_invalidates_entry(): void {
		$user_id = $this->create_full_author();

		$this->service()->get_author_data( $user_id );
		\update_user_meta( $user_id, 'apbl_author_position', 'Updated Title' );

		$this->service()->clear_cache( $user_id );

		$fresh = $this->service()->get_author_data( $user_id );
		$this->assertSame( 'Updated Title', $fresh['position'] );
	}

	public function test_clear_cache_without_id_clears_everything(): void {
		$user_id = $this->create_full_author();

		$this->service()->get_author_data( $user_id );
		\update_user_meta( $user_id, 'apbl_author_position', 'Final' );

		$this->service()->clear_cache();

		$fresh = $this->service()->get_author_data( $user_id );
		$this->assertSame( 'Final', $fresh['position'] );
	}

	public function test_clear_user_cache_hooked_to_profile_update(): void {
		$user_id = $this->create_full_author();

		$this->service()->get_author_data( $user_id );

		// Trigger profile_update which the service hooks into. Pass two args
		// because default callbacks like default_password_nag_edit_user
		// require old_user_data.
		\update_user_meta( $user_id, 'apbl_author_position', 'Edited' );
		\do_action( 'profile_update', $user_id, \get_userdata( $user_id ) );

		$fresh = $this->service()->get_author_data( $user_id );
		$this->assertSame( 'Edited', $fresh['position'] );
	}

	public function test_get_authors_returns_empty_when_no_users_match(): void {
		// Filter by a role nobody has.
		$results = $this->service()->get_authors( array( 'this-role-does-not-exist' ) );
		$this->assertSame( array(), $results );
	}

	public function test_get_authors_includes_created_author(): void {
		$user_id = $this->create_full_author();

		$results = $this->service()->get_authors( array( 'author' ) );

		$ids = array_map( static fn( $a ) => $a['id'], $results );
		$this->assertContains( $user_id, $ids );
	}

	public function test_get_authors_default_roles_include_administrator(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;

		$results = $this->service()->get_authors();
		$ids     = array_map( static fn( $a ) => $a['id'], $results );

		$this->assertContains( $admin_id, $ids );
	}

	public function test_author_data_filter_can_modify_output(): void {
		$user_id = $this->create_full_author();

		\add_filter(
			'author_profile_blocks_author_data',
			static function ( $data ) {
				$data['position'] = 'Filtered';
				return $data;
			}
		);

		$data = $this->service()->get_author_data( $user_id );
		$this->assertSame( 'Filtered', $data['position'] );
	}

	public function test_get_avatar_url_returns_string(): void {
		$user_id = $this->create_author();
		$url     = $this->service()->get_avatar_url( array( 'id' => $user_id ) );

		$this->assertIsString( $url );
		$this->assertNotEmpty( $url );
	}

	public function test_get_social_profiles_returns_empty_template_when_unset(): void {
		$user_id = $this->create_author();
		$result  = $this->service()->get_social_profiles( array( 'id' => $user_id ) );

		$this->assertSame(
			array(
				'facebook'  => '',
				'twitter'   => '',
				'linkedin'  => '',
				'instagram' => '',
				'website'   => '',
			),
			$result
		);
	}

	public function test_get_social_profiles_returns_stored_value(): void {
		$user_id = $this->create_full_author();
		$result  = $this->service()->get_social_profiles( array( 'id' => $user_id ) );

		$this->assertSame( 'https://facebook.com/test', $result['facebook'] );
	}

	public function test_get_member_since_label_falls_back_to_default(): void {
		$user_id = $this->create_author();
		$this->assertSame(
			'Member since',
			$this->service()->get_member_since_label( array( 'id' => $user_id ) )
		);
	}

	public function test_get_member_since_label_returns_custom_value(): void {
		$user_id = $this->create_full_author();
		$this->assertSame(
			'Writing since',
			$this->service()->get_member_since_label( array( 'id' => $user_id ) )
		);
	}

	public function test_register_rest_fields_registers_user_fields(): void {
		// Trigger rest_api_init so service registers fields.
		\do_action( 'rest_api_init' );

		global $wp_rest_additional_fields;
		$user_fields = $wp_rest_additional_fields['user'] ?? array();

		$this->assertArrayHasKey( 'avatar_url', $user_fields );
		$this->assertArrayHasKey( 'author_position', $user_fields );
		$this->assertArrayHasKey( 'author_description', $user_fields );
		$this->assertArrayHasKey( 'social_profiles', $user_fields );
		$this->assertArrayHasKey( 'registered_date', $user_fields );
		$this->assertArrayHasKey( 'member_since_label', $user_fields );
	}
}
