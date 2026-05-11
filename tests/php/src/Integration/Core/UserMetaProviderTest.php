<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Core;

use AuthorProfileBlocks\Core\User_Meta_Provider;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for User_Meta_Provider.
 */
class UserMetaProviderTest extends IntegrationTestCase {

	public function test_implements_meta_data_provider_interface(): void {
		$provider = new User_Meta_Provider();
		$this->assertInstanceOf( 'AuthorProfileBlocks\\Core\\MetaDataProvider', $provider );
	}

	public function test_add_meta_field_returns_self_for_chaining(): void {
		$provider = new User_Meta_Provider();
		$result   = $provider->add_meta_field( 'apbl_test_key', array( 'type' => 'string' ) );

		$this->assertSame( $provider, $result );
	}

	public function test_register_meta_fields_registers_added_keys(): void {
		$provider = new User_Meta_Provider();
		$provider->add_meta_field(
			'apbl_test_meta_field',
			array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
			)
		);
		$provider->register_meta_fields();

		$registered = \get_registered_meta_keys( 'user' );
		$this->assertArrayHasKey( 'apbl_test_meta_field', $registered );
	}

	public function test_get_meta_returns_stored_value(): void {
		$provider = new User_Meta_Provider();
		$user_id  = $this->create_author();

		\update_user_meta( $user_id, 'apbl_test_get', 'hello' );

		$this->assertSame( 'hello', $provider->get_meta( $user_id, 'apbl_test_get', true ) );
	}

	public function test_get_meta_returns_empty_string_when_missing(): void {
		$provider = new User_Meta_Provider();
		$user_id  = $this->create_author();

		$this->assertSame( '', $provider->get_meta( $user_id, 'apbl_does_not_exist', true ) );
	}

	public function test_update_meta_writes_value(): void {
		$provider = new User_Meta_Provider();
		$user_id  = $this->create_author();

		$result = $provider->update_meta( $user_id, 'apbl_test_update', 'value' );

		$this->assertNotFalse( $result );
		$this->assertSame( 'value', \get_user_meta( $user_id, 'apbl_test_update', true ) );
	}

	public function test_update_meta_overwrites_existing_value(): void {
		$provider = new User_Meta_Provider();
		$user_id  = $this->create_author();

		\update_user_meta( $user_id, 'apbl_test_overwrite', 'original' );
		$provider->update_meta( $user_id, 'apbl_test_overwrite', 'replaced' );

		$this->assertSame( 'replaced', \get_user_meta( $user_id, 'apbl_test_overwrite', true ) );
	}

	public function test_update_meta_supports_array_values(): void {
		$provider = new User_Meta_Provider();
		$user_id  = $this->create_author();

		$provider->update_meta(
			$user_id,
			'apbl_test_array',
			array( 'a' => 1, 'b' => 2 )
		);

		$stored = \get_user_meta( $user_id, 'apbl_test_array', true );
		$this->assertSame( array( 'a' => 1, 'b' => 2 ), $stored );
	}
}
