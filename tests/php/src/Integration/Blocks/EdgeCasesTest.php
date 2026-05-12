<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\AuthorProfileBlock;
use AuthorProfileBlocks\Blocks\AuthorGridBlock;
use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Blocks\Author_Carousel_Block;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Edge-case and negative-case rendering tests for all four blocks.
 *
 * Direct render_callback() invocations are only safe for error paths (the
 * blocks bail before calling get_block_wrapper_attributes(), which requires
 * WP_Block context). Happy-path scenarios go through render_block() so the
 * editor-side WP_Block plumbing is set up correctly.
 */
class EdgeCasesTest extends IntegrationTestCase {

	/* -----------------------------------------------------------------
	 *  Author Profile (single-author block) — error paths via direct call
	 * --------------------------------------------------------------- */

	public function test_profile_block_handles_negative_author_id(): void {
		// (int) -1 stays -1, truthy → service hits get_user_by → no user found.
		$html = ( new AuthorProfileBlock() )->render_callback(
			array( 'authorId' => -1 ),
			'',
			null
		);
		$this->assertStringContainsString( 'Author not found', $html );
	}

	public function test_profile_block_handles_null_author_id(): void {
		$html = ( new AuthorProfileBlock() )->render_callback(
			array( 'authorId' => null ),
			'',
			null
		);
		$this->assertStringContainsString( 'Please select', $html );
	}

	public function test_profile_block_handles_deleted_user(): void {
		$id = $this->create_author();
		\wp_delete_user( $id );
		$this->created_user_ids = array_diff( $this->created_user_ids, array( $id ) );

		$html = ( new AuthorProfileBlock() )->render_callback(
			array( 'authorId' => $id ),
			'',
			null
		);
		$this->assertStringContainsString( 'Author not found', $html );
	}

	/* -----------------------------------------------------------------
	 *  Author Profile — happy paths via render_block()
	 * --------------------------------------------------------------- */

	public function test_profile_block_handles_string_author_id(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block( 'author-profile', array( 'authorId' => (string) $id ) );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	public function test_profile_block_handles_unknown_content_order(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-profile',
			array( 'authorId' => $id, 'contentOrder' => 'no-such-order' )
		);
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	public function test_profile_block_escapes_custom_css_class(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'       => $id,
				'customCssClass' => '"><script>alert(1)</script>',
			)
		);
		$this->assertStringNotContainsString( '<script>alert(1)</script>', $html );
	}

	public function test_profile_block_escapes_more_content_dangerous_tags(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'        => $id,
				'showMoreContent' => true,
				'moreContent'     => '<script>steal()</script><p>OK</p>',
			)
		);
		$this->assertStringNotContainsString( '<script>steal()</script>', $html );
	}

	public function test_profile_block_renders_author_with_html_in_name(): void {
		$id   = $this->create_full_author( array( 'display_name' => 'A<script>X</script>B' ) );
		$html = $this->render_block( 'author-profile', array( 'authorId' => $id ) );

		$this->assertStringNotContainsString( '<script>X</script>', $html );
		$this->assertStringContainsString( '&lt;script&gt;', $html );
	}

	public function test_profile_block_handles_user_with_empty_meta(): void {
		$id   = $this->create_author();
		$html = $this->render_block( 'author-profile', array( 'authorId' => $id ) );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	public function test_profile_block_handles_malformed_social_profiles_meta(): void {
		$id = $this->create_author();
		\update_user_meta( $id, 'apbl_social_profiles', 'this-is-not-an-array' );

		$html = $this->render_block(
			'author-profile',
			array( 'authorId' => $id, 'showSocialLinks' => true )
		);
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	/* -----------------------------------------------------------------
	 *  Multi-author blocks (grid / list / carousel)
	 * --------------------------------------------------------------- */

	public function multi_block_slugs(): array {
		return array(
			'grid'     => array( 'author-grid' ),
			'list'     => array( 'author-list' ),
			'carousel' => array( 'author-carousel' ),
		);
	}

	public function multi_block_classes(): array {
		return array(
			'grid'     => array( AuthorGridBlock::class ),
			'list'     => array( Author_List_Block::class ),
			'carousel' => array( Author_Carousel_Block::class ),
		);
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_string_ids( string $slug ): void {
		$id   = $this->create_full_author( array( 'display_name' => 'StringAuthor' ) );
		$html = $this->render_block( $slug, array( 'authorIds' => array( (string) $id ) ) );
		$this->assertStringContainsString( 'StringAuthor', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_duplicate_ids( string $slug ): void {
		$id   = $this->create_full_author( array( 'display_name' => 'DupOnce' ) );
		$html = $this->render_block( $slug, array( 'authorIds' => array( $id, $id, $id ) ) );

		$this->assertStringNotContainsString( 'apbl-error-message', $html );
		$this->assertSame(
			3,
			substr_count( $html, 'DupOnce' ),
			'duplicates should render once per occurrence'
		);
	}

	/**
	 * @dataProvider multi_block_classes
	 */
	public function test_multi_block_with_zero_ids_renders_error( string $class ): void {
		$html = ( new $class() )->render_callback(
			array( 'authorIds' => array( 0, 0, 0 ) ),
			'',
			null
		);
		$this->assertStringContainsString( 'apbl-error-message', $html );
	}

	/**
	 * @dataProvider multi_block_classes
	 */
	public function test_multi_block_with_negative_ids_renders_error( string $class ): void {
		$html = ( new $class() )->render_callback(
			array( 'authorIds' => array( -1, -2 ) ),
			'',
			null
		);
		$this->assertStringContainsString( 'apbl-error-message', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_null_in_authorIds( string $slug ): void {
		$id   = $this->create_full_author( array( 'display_name' => 'WithNullVal' ) );
		$html = $this->render_block( $slug, array( 'authorIds' => array( null, $id, null ) ) );
		$this->assertStringContainsString( 'WithNullVal', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_string_max_authors( string $slug ): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'CapA' ) ),
			$this->create_full_author( array( 'display_name' => 'CapB' ) ),
			$this->create_full_author( array( 'display_name' => 'CapC' ) ),
		);
		$html = $this->render_block(
			$slug,
			array( 'authorIds' => $ids, 'maxAuthors' => '2' )
		);
		$this->assertStringContainsString( 'CapA', $html );
		$this->assertStringContainsString( 'CapB', $html );
		$this->assertStringNotContainsString( 'CapC', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_negative_max_authors( string $slug ): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'NegA' ) ),
			$this->create_full_author( array( 'display_name' => 'NegB' ) ),
		);
		$html = $this->render_block(
			$slug,
			array( 'authorIds' => $ids, 'maxAuthors' => -5 )
		);
		// Negative limit treated as no limit (>0 check fails).
		$this->assertStringContainsString( 'NegA', $html );
		$this->assertStringContainsString( 'NegB', $html );
	}

	/**
	 * @dataProvider multi_block_classes
	 */
	public function test_multi_block_with_invalid_role_filter( string $class ): void {
		$id = $this->create_full_author( array( 'role' => 'author' ) );

		$html = ( new $class() )->render_callback(
			array( 'authorIds' => array( $id ), 'authorRole' => 'no_such_role' ),
			'',
			null
		);
		$this->assertStringContainsString( 'No authors found', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_empty_role_filter_does_not_filter( string $slug ): void {
		$id   = $this->create_full_author( array( 'role' => 'author', 'display_name' => 'NoFilter' ) );
		$html = $this->render_block( $slug, array( 'authorIds' => array( $id ), 'authorRole' => '' ) );
		$this->assertStringContainsString( 'NoFilter', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_skips_deleted_authors( string $slug ): void {
		$keep = $this->create_full_author( array( 'display_name' => 'KeepUser' ) );
		$drop = $this->create_full_author( array( 'display_name' => 'DropUser' ) );

		\wp_delete_user( $drop );
		$this->created_user_ids = array_diff( $this->created_user_ids, array( $drop ) );

		$html = $this->render_block( $slug, array( 'authorIds' => array( $keep, $drop ) ) );
		$this->assertStringContainsString( 'KeepUser', $html );
		$this->assertStringNotContainsString( 'DropUser', $html );
	}

	/**
	 * @dataProvider multi_block_classes
	 */
	public function test_multi_block_handles_role_filter_case_mismatch( string $class ): void {
		$id = $this->create_full_author( array( 'role' => 'author', 'display_name' => 'CaseTest' ) );

		// Roles in WP are case-sensitive — "Author" must not match "author".
		$html = ( new $class() )->render_callback(
			array( 'authorIds' => array( $id ), 'authorRole' => 'Author' ),
			'',
			null
		);
		$this->assertStringContainsString( 'No authors found', $html );
	}

	/**
	 * @dataProvider multi_block_classes
	 */
	public function test_multi_block_cache_isolated_per_attribute_set( string $class ): void {
		$id    = $this->create_full_author( array( 'display_name' => 'CacheIso' ) );
		$block = new $class();

		$html_a = $block->render_callback(
			array( 'authorIds' => array( $id ), 'showSocial' => true ),
			'',
			null
		);
		$html_b = $block->render_callback(
			array( 'authorIds' => array( $id ), 'showSocial' => false ),
			'',
			null
		);

		// Same ids, different attrs → different cache key. Note: error paths
		// don't cache, so use happy paths via render_block in real fixtures.
		$this->assertNotSame( $html_a, $html_b );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_with_invalid_animation_type( string $slug ): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			$slug,
			array( 'authorIds' => array( $id ), 'animationType' => 'totally-bogus' )
		);
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'has-totally-bogus-animation', $html );
	}

	/**
	 * @dataProvider multi_block_slugs
	 */
	public function test_multi_block_zero_max_authors_means_no_limit( string $slug ): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'ZeroMaxA' ) ),
			$this->create_full_author( array( 'display_name' => 'ZeroMaxB' ) ),
		);
		$html = $this->render_block(
			$slug,
			array( 'authorIds' => $ids, 'maxAuthors' => 0 )
		);
		$this->assertStringContainsString( 'ZeroMaxA', $html );
		$this->assertStringContainsString( 'ZeroMaxB', $html );
	}

	public function test_multi_block_render_item_handles_missing_optional_fields(): void {
		$grid    = new AuthorGridBlock();
		$minimal = array( 'id' => 99999, 'title' => 'StubAuthor' );

		$html = $grid->render_author_item( $minimal, array( 'showSocial' => true ) );

		$this->assertStringContainsString( 'apbl-author-grid-item', $html );
		$this->assertStringContainsString( 'StubAuthor', $html );
	}
}
