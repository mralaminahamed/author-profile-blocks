<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * End-to-end coverage for the user-selection feature across the three
 * multi-author blocks (grid, list, carousel).
 *
 * The plugin selects authors via `authorIds` (array), narrows via `authorRole`,
 * and caps via `maxAuthors`. These tests verify each axis on its own and in
 * combination, and ensure invalid/missing inputs degrade gracefully.
 */
class UserSelectionTest extends IntegrationTestCase {

	/**
	 * Block-name → expected wrapper class fragment used to verify a successful
	 * render across all multi-author blocks.
	 *
	 * @return array<string, array{0:string, 1:string}>
	 */
	public function multi_author_blocks_provider(): array {
		return array(
			'grid'     => array( 'author-grid', 'apbl-author-grid' ),
			'list'     => array( 'author-list', 'apbl-author-list' ),
			'carousel' => array( 'author-carousel', 'apbl-author-carousel' ),
		);
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_renders_only_selected_authors( string $block, string $marker ): void {
		$alice = $this->create_full_author( array( 'display_name' => 'Alice' ) );
		$bob   = $this->create_full_author( array( 'display_name' => 'Bob' ) );
		$_     = $this->create_full_author( array( 'display_name' => 'Carol' ) );

		$html = $this->render_block( $block, array( 'authorIds' => array( $alice, $bob ) ) );

		$this->assertStringContainsString( $marker, $html );
		$this->assertStringContainsString( 'Alice', $html );
		$this->assertStringContainsString( 'Bob', $html );
		$this->assertStringNotContainsString( 'Carol', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_preserves_selection_order( string $block, string $marker ): void {
		$alice = $this->create_full_author( array( 'display_name' => 'Alpha' ) );
		$bob   = $this->create_full_author( array( 'display_name' => 'Bravo' ) );
		$carol = $this->create_full_author( array( 'display_name' => 'Charlie' ) );

		$html = $this->render_block( $block, array( 'authorIds' => array( $carol, $alice, $bob ) ) );

		$pos_charlie = strpos( $html, 'Charlie' );
		$pos_alpha   = strpos( $html, 'Alpha' );
		$pos_bravo   = strpos( $html, 'Bravo' );

		$this->assertNotFalse( $pos_charlie );
		$this->assertNotFalse( $pos_alpha );
		$this->assertNotFalse( $pos_bravo );
		$this->assertLessThan( $pos_alpha, $pos_charlie, 'Charlie should render before Alpha' );
		$this->assertLessThan( $pos_bravo, $pos_alpha, 'Alpha should render before Bravo' );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_string_ids_cast_to_int( string $block, string $marker ): void {
		$id = $this->create_full_author( array( 'display_name' => 'StringSelected' ) );

		$html = $this->render_block( $block, array( 'authorIds' => array( (string) $id ) ) );

		$this->assertStringContainsString( 'StringSelected', $html );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	/**
	 * Empty attributes go through the block render_callback directly because
	 * a serialized block comment with `[]` attrs is parsed as no attributes
	 * (Gutenberg expects a JSON object, not an array).
	 *
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_empty_selection_renders_error( string $block, string $marker ): void {
		$class = array(
			'author-grid'     => \AuthorProfileBlocks\Blocks\AuthorGridBlock::class,
			'author-list'     => \AuthorProfileBlocks\Blocks\Author_List_Block::class,
			'author-carousel' => \AuthorProfileBlocks\Blocks\Author_Carousel_Block::class,
		)[ $block ];

		$instance = new $class();
		$html     = $instance->render_callback( array(), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_only_invalid_ids_renders_no_authors_found( string $block, string $marker ): void {
		$html = $this->render_block( $block, array( 'authorIds' => array( 999999, 888888 ) ) );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'No authors found', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_mixed_valid_invalid_ids_filters_invalid( string $block, string $marker ): void {
		$valid = $this->create_full_author( array( 'display_name' => 'ValidUser' ) );

		$html = $this->render_block(
			$block,
			array( 'authorIds' => array( $valid, 999999, 888888 ) )
		);

		$this->assertStringContainsString( 'ValidUser', $html );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_role_filter_narrows_results( string $block, string $marker ): void {
		$author_id = $this->create_full_author( array( 'role' => 'author', 'display_name' => 'JustAuthor' ) );
		$editor_id = $this->create_full_author( array( 'role' => 'editor', 'display_name' => 'JustEditor' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $author_id, $editor_id ),
				'authorRole' => 'editor',
			)
		);

		$this->assertStringContainsString( 'JustEditor', $html );
		$this->assertStringNotContainsString( 'JustAuthor', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_role_filter_excluding_all_renders_no_authors_found( string $block, string $marker ): void {
		$id = $this->create_full_author( array( 'role' => 'author' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $id ),
				'authorRole' => 'editor',
			)
		);

		$this->assertStringContainsString( 'No authors found', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_max_authors_caps_output( string $block, string $marker ): void {
		$one   = $this->create_full_author( array( 'display_name' => 'One' ) );
		$two   = $this->create_full_author( array( 'display_name' => 'Two' ) );
		$three = $this->create_full_author( array( 'display_name' => 'Three' ) );
		$four  = $this->create_full_author( array( 'display_name' => 'Four' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $one, $two, $three, $four ),
				'maxAuthors' => 2,
			)
		);

		$this->assertStringContainsString( 'One', $html );
		$this->assertStringContainsString( 'Two', $html );
		$this->assertStringNotContainsString( 'Three', $html );
		$this->assertStringNotContainsString( 'Four', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_max_authors_zero_is_no_limit( string $block, string $marker ): void {
		$one = $this->create_full_author( array( 'display_name' => 'AAA' ) );
		$two = $this->create_full_author( array( 'display_name' => 'BBB' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $one, $two ),
				'maxAuthors' => 0,
			)
		);

		$this->assertStringContainsString( 'AAA', $html );
		$this->assertStringContainsString( 'BBB', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_max_authors_larger_than_count_returns_all( string $block, string $marker ): void {
		$one = $this->create_full_author( array( 'display_name' => 'PersonOne' ) );
		$two = $this->create_full_author( array( 'display_name' => 'PersonTwo' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $one, $two ),
				'maxAuthors' => 99,
			)
		);

		$this->assertStringContainsString( 'PersonOne', $html );
		$this->assertStringContainsString( 'PersonTwo', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_role_filter_then_max_limit( string $block, string $marker ): void {
		$author_a = $this->create_full_author( array( 'role' => 'author', 'display_name' => 'AuthorA' ) );
		$editor_a = $this->create_full_author( array( 'role' => 'editor', 'display_name' => 'EditorA' ) );
		$editor_b = $this->create_full_author( array( 'role' => 'editor', 'display_name' => 'EditorB' ) );
		$editor_c = $this->create_full_author( array( 'role' => 'editor', 'display_name' => 'EditorC' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'  => array( $author_a, $editor_a, $editor_b, $editor_c ),
				'authorRole' => 'editor',
				'maxAuthors' => 2,
			)
		);

		$this->assertStringContainsString( 'EditorA', $html );
		$this->assertStringContainsString( 'EditorB', $html );
		$this->assertStringNotContainsString( 'EditorC', $html );
		$this->assertStringNotContainsString( 'AuthorA', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_deleted_author_in_selection_skipped( string $block, string $marker ): void {
		$keeper  = $this->create_full_author( array( 'display_name' => 'Keeper' ) );
		$dropped = $this->create_full_author( array( 'display_name' => 'Dropped' ) );

		// Delete one author then render.
		\wp_delete_user( $dropped );
		// Remove from cleanup list since already deleted.
		$this->created_user_ids = array_diff( $this->created_user_ids, array( $dropped ) );

		$html = $this->render_block( $block, array( 'authorIds' => array( $keeper, $dropped ) ) );

		$this->assertStringContainsString( 'Keeper', $html );
		$this->assertStringNotContainsString( 'Dropped', $html );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	/**
	 * Author Profile (single) selection: authorId points to existing user.
	 */
	public function test_single_author_block_selects_via_author_id(): void {
		$id = $this->create_full_author( array( 'display_name' => 'SingleSelected' ) );

		$html = $this->render_block( 'author-profile', array( 'authorId' => $id ) );

		$this->assertStringContainsString( 'SingleSelected', $html );
		$this->assertStringNotContainsString( 'apbl-error-message', $html );
	}

	public function test_single_author_block_with_string_author_id(): void {
		$id = $this->create_full_author( array( 'display_name' => 'StringId' ) );

		$html = $this->render_block( 'author-profile', array( 'authorId' => (string) $id ) );

		$this->assertStringContainsString( 'StringId', $html );
	}

	public function test_single_author_block_with_unknown_author_id(): void {
		$html = $this->render_block( 'author-profile', array( 'authorId' => 999999 ) );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'Author not found', $html );
	}

	/**
	 * @dataProvider multi_author_blocks_provider
	 */
	public function test_selection_renders_position_and_social( string $block, string $marker ): void {
		$id = $this->create_full_author( array( 'display_name' => 'FullProfile' ) );

		$html = $this->render_block(
			$block,
			array(
				'authorIds'   => array( $id ),
				'showSocial'  => true,
				'showPosition' => true,
			)
		);

		$this->assertStringContainsString( 'FullProfile', $html );
		$this->assertStringContainsString( 'Senior Editor', $html );
		// Social is wired to facebook URL.
		$this->assertStringContainsString( 'facebook.com/test', $html );
	}
}
