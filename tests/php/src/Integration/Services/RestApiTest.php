<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Services;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;
use WP_REST_Request;
use WP_REST_Server;

/**
 * REST API integration tests for the Author_Profile_Service.
 *
 * The service registers fields onto the `user` REST resource. These tests
 * boot a real REST server and verify the fields are exposed and populated.
 */
class RestApiTest extends IntegrationTestCase {

	private WP_REST_Server $server;

	public function set_up(): void {
		parent::set_up();

		global $wp_rest_server;
		$wp_rest_server = new WP_REST_Server();
		$this->server   = $wp_rest_server;

		\do_action( 'rest_api_init' );
	}

	public function tear_down(): void {
		global $wp_rest_server;
		$wp_rest_server = null;
		parent::tear_down();
	}

	private function fetch_user( int $user_id ): array {
		$request = new WP_REST_Request( 'GET', '/wp/v2/users/' . $user_id );
		$request->set_param( 'context', 'edit' );

		// Need permission to use edit context.
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$response = $this->server->dispatch( $request );

		$this->assertSame( 200, $response->get_status(), 'REST request failed: ' . wp_json_encode( $response->get_data() ) );

		return $response->get_data();
	}

	public function test_user_response_includes_all_custom_fields(): void {
		$id   = $this->create_full_author();
		$data = $this->fetch_user( $id );

		$this->assertArrayHasKey( 'avatar_url', $data );
		$this->assertArrayHasKey( 'author_position', $data );
		$this->assertArrayHasKey( 'author_description', $data );
		$this->assertArrayHasKey( 'social_profiles', $data );
		$this->assertArrayHasKey( 'registered_date', $data );
		$this->assertArrayHasKey( 'member_since_label', $data );
	}

	public function test_user_response_returns_stored_position(): void {
		$id   = $this->create_full_author();
		$data = $this->fetch_user( $id );

		$this->assertSame( 'Senior Editor', $data['author_position'] );
	}

	public function test_user_response_returns_social_profiles_object(): void {
		$id   = $this->create_full_author();
		$data = $this->fetch_user( $id );

		$this->assertIsArray( $data['social_profiles'] );
		$this->assertSame( 'https://twitter.com/test', $data['social_profiles']['twitter'] );
	}

	public function test_user_response_returns_default_social_profiles_when_unset(): void {
		$id   = $this->create_author();
		$data = $this->fetch_user( $id );

		$this->assertSame(
			array(
				'facebook'  => '',
				'twitter'   => '',
				'linkedin'  => '',
				'instagram' => '',
				'website'   => '',
			),
			$data['social_profiles']
		);
	}

	public function test_user_response_returns_custom_member_since_label(): void {
		$id   = $this->create_full_author();
		$data = $this->fetch_user( $id );

		$this->assertSame( 'Writing since', $data['member_since_label'] );
	}

	public function test_user_response_falls_back_to_default_member_since_label(): void {
		$id   = $this->create_author();
		$data = $this->fetch_user( $id );

		$this->assertSame( 'Member since', $data['member_since_label'] );
	}

	public function test_user_response_includes_registered_date(): void {
		$id   = $this->create_author( array( 'user_registered' => '2023-06-15 12:00:00' ) );
		$data = $this->fetch_user( $id );

		$this->assertNotEmpty( $data['registered_date'] );
		$this->assertIsString( $data['registered_date'] );
	}

	public function test_avatar_url_field_is_non_empty_string(): void {
		$id   = $this->create_author();
		$data = $this->fetch_user( $id );

		$this->assertIsString( $data['avatar_url'] );
		$this->assertNotEmpty( $data['avatar_url'] );
	}
}
