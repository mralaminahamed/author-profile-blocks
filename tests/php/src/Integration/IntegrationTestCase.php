<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration;

use WP_UnitTestCase;

/**
 * Base integration test case for Author Profile Blocks.
 *
 * Provides shared helpers for creating authors with profile meta, building
 * realistic block attributes, and resetting the in-process render cache
 * between tests.
 */
abstract class IntegrationTestCase extends WP_UnitTestCase {

	/**
	 * IDs of users created during a test, removed in tearDown.
	 *
	 * @var int[]
	 */
	protected array $created_user_ids = array();

	/**
	 * Reset shared state before each test.
	 */
	public function set_up(): void {
		parent::set_up();

		// WP test framework resets registered meta and action handlers between
		// tests; re-register plugin user-meta fields and service hooks so
		// tests see the documented contract.
		\author_profile_blocks()->register_meta_field();
		\author_profile_blocks()->get_author_profile_service()->init();

		// Drop persistent author cache between tests so we test fresh paths.
		\wp_cache_flush_group( 'author_profile_blocks' );
		\wp_cache_flush_group( 'author_profile_blocks_authors' );

		// Clear in-memory service cache too.
		$service = \author_profile_blocks()->get_author_profile_service();
		$service->clear_cache();
	}

	/**
	 * Create an author user with optional Author Profile Blocks meta.
	 *
	 * @param array<string, mixed> $user_args  Args for wp_insert_user.
	 * @param array<string, mixed> $meta       Map of meta_key => value to attach.
	 *
	 * @return int New user ID.
	 */
	protected function create_author( array $user_args = array(), array $meta = array() ): int {
		$defaults = array(
			'role'          => 'author',
			'user_login'    => 'apbl_author_' . \wp_generate_password( 8, false ),
			'user_email'    => 'apbl_' . \wp_generate_password( 8, false ) . '@example.com',
			'user_pass'     => 'password',
			'display_name'  => 'Test Author',
			'first_name'    => 'Test',
			'last_name'     => 'Author',
			'user_registered' => '2024-01-15 10:00:00',
		);

		$args    = array_merge( $defaults, $user_args );
		$user_id = \wp_insert_user( $args );

		$this->assertIsInt( $user_id, 'Author user creation failed: ' . print_r( $user_id, true ) );

		foreach ( $meta as $key => $value ) {
			\update_user_meta( $user_id, $key, $value );
		}

		$this->created_user_ids[] = $user_id;

		return (int) $user_id;
	}

	/**
	 * Render a registered block via do_blocks() so WP_Block context (used by
	 * get_block_wrapper_attributes) is set up correctly.
	 *
	 * @param string               $block_name Short name without namespace (e.g. "author-profile").
	 * @param array<string, mixed> $attributes Block attributes JSON-encoded into the comment.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_block( string $block_name, array $attributes ): string {
		$json    = \wp_json_encode( $attributes );
		$comment = '<!-- wp:author-profile-blocks/' . $block_name . ' ' . $json . ' /-->';
		return \do_blocks( $comment );
	}

	/**
	 * Simulate the block editor REST context so render_error_message() returns HTML.
	 *
	 * Call in a test; the filter is automatically removed in tear_down().
	 */
	protected function simulate_editor_context(): void {
		add_filter( 'author_profile_blocks_is_editor_context', '__return_true' );
	}

	/**
	 * Tear down created authors and reset editor context filter.
	 */
	public function tear_down(): void {
		remove_filter( 'author_profile_blocks_is_editor_context', '__return_true' );

		foreach ( $this->created_user_ids as $user_id ) {
			\wp_delete_user( $user_id );
		}
		$this->created_user_ids = array();

		parent::tear_down();
	}

	/**
	 * Build a realistic author with all profile meta populated.
	 *
	 * @param array<string, mixed> $overrides Optional user_args overrides.
	 *
	 * @return int User ID.
	 */
	protected function create_full_author( array $overrides = array() ): int {
		return $this->create_author(
			$overrides,
			array(
				'apbl_author_position'    => 'Senior Editor',
				'apbl_author_description' => 'Long-form author bio with <strong>formatting</strong>.',
				'apbl_member_since_label' => 'Writing since',
				'apbl_social_profiles'    => array(
					'facebook'  => 'https://facebook.com/test',
					'twitter'   => 'https://twitter.com/test',
					'linkedin'  => 'https://linkedin.com/in/test',
					'instagram' => 'https://instagram.com/test',
					'website'   => 'https://example.com',
				),
			)
		);
	}
}
