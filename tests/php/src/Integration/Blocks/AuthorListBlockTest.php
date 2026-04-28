<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for Author_List_Block.
 */
class AuthorListBlockTest extends IntegrationTestCase {

	private Author_List_Block $block;

	public function set_up(): void {
		parent::set_up();
		$this->block = new Author_List_Block();
	}

	public function test_block_name(): void {
		$this->assertSame( 'author-list', $this->block->get_block_name() );
	}

	public function test_render_callback_returns_error_when_no_authors_selected(): void {
		$html = $this->block->render_callback( array(), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'list', $html );
	}

	public function test_render_callback_returns_error_when_role_filter_excludes_all(): void {
		$id = $this->create_author( array( 'role' => 'author' ) );

		$html = $this->block->render_callback(
			array( 'authorIds' => array( $id ), 'authorRole' => 'editor' ),
			'',
			null
		);

		$this->assertStringContainsString( 'No authors found', $html );
	}

	public function test_render_block_renders_each_display_style(): void {
		$id = $this->create_full_author( array( 'display_name' => 'Listed Person' ) );

		foreach ( array( 'compact', 'detailed', 'minimal' ) as $style ) {
			$html = $this->render_block(
				'author-list',
				array( 'authorIds' => array( $id ), 'displayStyle' => $style )
			);

			$this->assertNotEmpty( $html, "displayStyle={$style} produced empty output" );
			$this->assertStringNotContainsString( 'apbl-error-message', $html );
			$this->assertStringContainsString( "is-style-{$style}", $html );
		}
	}

	public function test_render_block_compact_and_detailed_show_author_name(): void {
		$id = $this->create_full_author( array( 'display_name' => 'Listed Person' ) );

		foreach ( array( 'compact', 'detailed' ) as $style ) {
			$html = $this->render_block(
				'author-list',
				array( 'authorIds' => array( $id ), 'displayStyle' => $style )
			);

			$this->assertStringContainsString( 'Listed Person', $html, "displayStyle={$style} missing name" );
		}
	}

	public function test_render_author_item_includes_layout_preset(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->block->render_author_item(
			$author,
			array(
				'displayStyle'  => 'detailed',
				'layoutPreset'  => 'classic',
				'animationType' => 'slideUp',
				'hoverEffect'   => 'lift',
				'enableRounded' => true,
			)
		);

		$this->assertStringContainsString( 'apbl-author-list-item', $html );
		$this->assertStringContainsString( 'classic', $html );
		$this->assertStringContainsString( 'has-slideUp-animation', $html );
		$this->assertStringContainsString( 'has-lift-hover', $html );
		$this->assertStringContainsString( 'is-rounded', $html );
	}

	public function test_render_author_item_default_displays_compact_layout(): void {
		$id     = $this->create_full_author( array( 'display_name' => 'Compact Person' ) );
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->block->render_author_item( $author, array() );

		$this->assertStringContainsString( 'Compact Person', $html );
	}
}
