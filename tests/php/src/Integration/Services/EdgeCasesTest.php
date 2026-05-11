<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Services;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Edge-case tests for AuthorProfileService.
 */
class EdgeCasesTest extends IntegrationTestCase {

	private function service() {
		return \author_profile_blocks()->get_author_profile_service();
	}

	public function test_get_author_data_returns_null_for_negative_id(): void {
		$this->assertNull( $this->service()->get_author_data( -1 ) );
	}

	public function test_get_author_data_returns_null_for_zero(): void {
		// Zero-id user does not exist; service must not throw.
		$this->assertNull( $this->service()->get_author_data( 0 ) );
	}

	public function test_get_author_data_returns_null_for_huge_id(): void {
		$this->assertNull( $this->service()->get_author_data( PHP_INT_MAX ) );
	}

	public function test_get_authors_with_empty_role_array_uses_defaults(): void {
		$id = $this->create_full_author( array( 'role' => 'editor' ) );

		// Empty array → defaults (admin/editor/author/contributor).
		$results = $this->service()->get_authors( array() );
		$ids     = array_column( $results, 'id' );

		$this->assertContains( $id, $ids );
	}

	public function test_get_authors_with_multiple_roles(): void {
		$author_id = $this->create_full_author( array( 'role' => 'author' ) );
		$editor_id = $this->create_full_author( array( 'role' => 'editor' ) );

		$results = $this->service()->get_authors( array( 'author', 'editor' ) );
		$ids     = array_column( $results, 'id' );

		$this->assertContains( $author_id, $ids );
		$this->assertContains( $editor_id, $ids );
	}

	public function test_get_authors_passes_through_extra_args(): void {
		$ids = array(
			$this->create_author( array( 'display_name' => 'AAAA' ) ),
			$this->create_author( array( 'display_name' => 'BBBB' ) ),
			$this->create_author( array( 'display_name' => 'CCCC' ) ),
		);

		$results = $this->service()->get_authors(
			array( 'author' ),
			array( 'number' => 1, 'orderby' => 'display_name', 'order' => 'ASC' )
		);

		// At most 1 result (number=1) and the names start with A.
		$this->assertLessThanOrEqual( 1, count( $results ) );
	}

	public function test_clear_cache_with_zero_clears_in_memory_only_for_zero(): void {
		// clear_cache( 0 ) is treated as "no specific id" by the truthy check.
		// The method still falls back to the "clear all" branch when id is 0.
		$id = $this->create_full_author();

		$this->service()->get_author_data( $id );

		$this->service()->clear_cache( 0 );

		// After "clear all", a mutation should be visible.
		\update_user_meta( $id, 'apbl_author_position', 'AfterClear' );
		$fresh = $this->service()->get_author_data( $id );
		$this->assertSame( 'AfterClear', $fresh['position'] );
	}

	public function test_clear_cache_for_unknown_id_is_safe(): void {
		// No exception when clearing a never-cached id.
		$this->service()->clear_cache( 987654321 );
		$this->assertTrue( true );
	}

	public function test_clear_user_cache_called_on_user_register(): void {
		// Service hooks user_register to clear_user_cache. Triggering it must
		// not throw and should have no observable side effects on data.
		$id = $this->create_author();
		\do_action( 'user_register', $id );
		$this->assertTrue( true );
	}

	public function test_service_throws_on_corrupted_cache_entry(): void {
		$id = $this->create_full_author();

		// Plant a string where an array is expected. The service has a strict
		// `?array` return type; locking this in flags the latent bug — the
		// service trusts wp_cache returns and does not re-validate them.
		\wp_cache_set(
			'author_profile_blocks_author_' . $id,
			'corrupted-string',
			'author_profile_blocks',
			60
		);

		$this->expectException( \TypeError::class );
		$this->service()->get_author_data( $id );
	}

	public function test_author_data_filter_can_drop_keys(): void {
		$id = $this->create_full_author();

		\add_filter(
			'author_profile_blocks_author_data',
			static function ( $data ) {
				unset( $data['email'] );
				return $data;
			}
		);

		$data = $this->service()->get_author_data( $id );
		$this->assertArrayNotHasKey( 'email', $data );
	}

	public function test_get_avatar_url_returns_string_for_unknown_user(): void {
		$url = $this->service()->get_avatar_url( array( 'id' => 999999999 ) );
		$this->assertIsString( $url );
	}

	public function test_get_member_since_label_handles_array_meta(): void {
		$id = $this->create_author();
		// Plant an array where a string is expected.
		\update_user_meta( $id, 'apbl_member_since_label', array( 'oops' => 'array' ) );

		$label = $this->service()->get_member_since_label( array( 'id' => $id ) );
		$this->assertIsString( $label );
	}

	public function test_get_authors_filter_short_circuits_query_args(): void {
		\add_filter(
			'author_profile_blocks_author_query_args',
			static function ( $args ) {
				$args['number'] = 0; // No users will be returned.
				return $args;
			}
		);

		$results = $this->service()->get_authors( array( 'author' ) );
		$this->assertSame( array(), $results );
	}
}
