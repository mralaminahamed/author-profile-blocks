<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Core;

use AuthorProfileBlocks\Core\User_Meta_Provider;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Edge-case tests for User_Meta_Provider.
 */
class UserMetaProviderEdgeCasesTest extends IntegrationTestCase {

	public function test_get_meta_with_single_false_returns_array(): void {
		$p  = new User_Meta_Provider();
		$id = $this->create_author();

		\add_user_meta( $id, 'apbl_test_multi', 'one' );
		\add_user_meta( $id, 'apbl_test_multi', 'two' );

		$values = $p->get_meta( $id, 'apbl_test_multi', false );
		$this->assertIsArray( $values );
		$this->assertContains( 'one', $values );
		$this->assertContains( 'two', $values );
	}

	public function test_update_meta_with_null_value_clears_field(): void {
		$p  = new User_Meta_Provider();
		$id = $this->create_author();

		$p->update_meta( $id, 'apbl_test_null', 'starting' );
		$this->assertSame( 'starting', \get_user_meta( $id, 'apbl_test_null', true ) );

		$p->update_meta( $id, 'apbl_test_null', '' );
		$this->assertSame( '', \get_user_meta( $id, 'apbl_test_null', true ) );
	}

	public function test_update_meta_with_object_serializes(): void {
		$p   = new User_Meta_Provider();
		$id  = $this->create_author();
		$obj = (object) array( 'a' => 1 );

		$p->update_meta( $id, 'apbl_test_obj', $obj );
		$stored = \get_user_meta( $id, 'apbl_test_obj', true );
		$this->assertIsObject( $stored );
		$this->assertSame( 1, $stored->a );
	}

	public function test_add_meta_field_overwrites_existing_config(): void {
		$p = new User_Meta_Provider();
		$p->add_meta_field( 'apbl_test_overwrite_cfg', array( 'type' => 'string' ) );
		$p->add_meta_field( 'apbl_test_overwrite_cfg', array( 'type' => 'integer' ) );

		$p->register_meta_fields();

		$registered = \get_registered_meta_keys( 'user' );
		$this->assertSame( 'integer', $registered['apbl_test_overwrite_cfg']['type'] );
	}

	public function test_register_meta_fields_with_no_added_fields_is_noop(): void {
		$p = new User_Meta_Provider();
		$p->register_meta_fields();
		$this->assertTrue( true );
	}

	public function test_get_meta_for_nonexistent_user_returns_empty(): void {
		$p = new User_Meta_Provider();
		$this->assertSame( '', $p->get_meta( PHP_INT_MAX, 'anything', true ) );
	}

	public function test_update_meta_returns_id_on_first_insert(): void {
		$p  = new User_Meta_Provider();
		$id = $this->create_author();

		$result = $p->update_meta( $id, 'apbl_test_first_insert', 'value' );

		// First insert returns meta_id (int > 0); subsequent updates return true.
		$this->assertNotFalse( $result );
	}
}
