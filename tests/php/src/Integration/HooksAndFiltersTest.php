<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration;

/**
 * Verify the plugin's documented action/filter contract.
 *
 * Block-registration hooks (author_profile_blocks_register_blocks,
 * author_profile_blocks_block_args, author_profile_blocks_block_registered)
 * are exercised once during plugin bootstrap; re-firing them in a test causes
 * duplicate-registration warnings, so we verify those by inspecting hook
 * state instead of re-running registration.
 */
class HooksAndFiltersTest extends IntegrationTestCase {

	public function test_init_action_fires_during_bootstrap(): void {
		$this->assertGreaterThan( 0, \did_action( 'author_profile_blocks_init' ) );
	}

	public function test_blocks_registered_action_fires_during_bootstrap(): void {
		$this->assertGreaterThan( 0, \did_action( 'author_profile_blocks_blocks_registered' ) );
	}

	public function test_block_registered_action_fired_for_each_block(): void {
		// Captured during bootstrap; check via did_action count.
		$this->assertGreaterThanOrEqual( 4, \did_action( 'author_profile_blocks_block_registered' ) );
	}

	public function test_register_blocks_action_fires_during_bootstrap(): void {
		$this->assertGreaterThan( 0, \did_action( 'author_profile_blocks_register_blocks' ) );
	}

	public function test_authors_filter_can_modify_listing(): void {
		$id = $this->create_full_author();

		\add_filter(
			'author_profile_blocks_authors',
			static function ( $authors ) {
				foreach ( $authors as &$a ) {
					$a['filtered'] = true;
				}
				return $authors;
			}
		);

		$results = \author_profile_blocks()->get_authors( array( 'author' ) );
		$ids     = array_column( $results, 'id' );

		$this->assertContains( $id, $ids );
		foreach ( $results as $a ) {
			$this->assertTrue( $a['filtered'] ?? false );
		}
	}

	public function test_author_query_args_filter_modifies_query(): void {
		$captured = null;
		\add_filter(
			'author_profile_blocks_author_query_args',
			static function ( $args ) use ( &$captured ) {
				$captured = $args;
				return $args;
			}
		);

		\author_profile_blocks()->get_authors( array( 'editor' ), array( 'orderby' => 'login' ) );

		$this->assertIsArray( $captured );
		$this->assertSame( array( 'editor' ), $captured['role__in'] );
		$this->assertSame( 'login', $captured['orderby'] );
	}

	public function test_rendered_block_filter_fires(): void {
		$id = $this->create_full_author( array( 'display_name' => 'FilterTarget' ) );

		\add_filter(
			'author_profile_blocks_rendered_block',
			static function ( $content, $block, $name ) {
				return $content . '<!-- rendered:' . $name . ' -->';
			},
			10,
			3
		);

		$html = $this->render_block( 'author-profile', array( 'authorId' => $id ) );

		$this->assertStringContainsString( '<!-- rendered:author-profile -->', $html );
	}

	public function test_block_specific_rendered_filter_fires(): void {
		$id = $this->create_full_author( array( 'display_name' => 'GridFiltered' ) );

		\add_filter(
			'author_profile_blocks_rendered_author_grid',
			static fn( $content ) => $content . '<!-- grid-only -->'
		);

		$html = $this->render_block( 'author-grid', array( 'authorIds' => array( $id ) ) );

		$this->assertStringContainsString( '<!-- grid-only -->', $html );
	}

	public function test_locate_template_filter_can_redirect(): void {
		$called = false;
		\add_filter(
			'author_profile_blocks_locate_template',
			static function ( $path, $name ) use ( &$called ) {
				$called = true;
				return $path;
			},
			10,
			2
		);

		\author_profile_blocks()->locate_template( 'blocks/author-grid/grid.php' );

		$this->assertTrue( $called );
	}

	public function test_save_profile_fields_action_fires(): void {
		$admin_id = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin_id;
		\wp_set_current_user( $admin_id );

		$called      = false;
		$received_id = null;
		\add_action(
			'author_profile_blocks_save_profile_fields',
			static function ( $user_id ) use ( &$called, &$received_id ) {
				$called      = true;
				$received_id = $user_id;
			}
		);

		$user_id = $this->create_author();

		$_POST = array(
			'apbl_profile_nonce'   => \wp_create_nonce( 'apbl_save_profile_data' ),
			'apbl_author_position' => 'X',
		);

		\author_profile_blocks()->save_author_profile_fields( $user_id );

		$this->assertTrue( $called );
		$this->assertSame( $user_id, $received_id );

		$_POST = array();
	}

	public function test_get_template_filter_can_redirect(): void {
		$called = false;
		\add_filter(
			'author_profile_blocks_get_template',
			static function ( $template ) use ( &$called ) {
				$called = true;
				return $template;
			}
		);

		\author_profile_blocks()->get_template_html(
			'blocks/components/author-name.php',
			array(
				'author'     => array( 'title' => 'X' ),
				'attributes' => array(),
			)
		);

		$this->assertTrue( $called );
	}

	public function test_profile_fields_action_fires_when_displaying_form(): void {
		$user = \get_userdata( $this->create_author() );

		$received = null;
		\add_action(
			'author_profile_blocks_profile_fields',
			static function ( $u ) use ( &$received ) {
				$received = $u;
			}
		);

		ob_start();
		\author_profile_blocks()->add_author_profile_fields( $user );
		ob_end_clean();

		$this->assertNotNull( $received );
		$this->assertSame( $user->ID, $received->ID );
	}
}
