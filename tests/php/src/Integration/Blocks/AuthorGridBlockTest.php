<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\AuthorGridBlock;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for AuthorGridBlock.
 */
class AuthorGridBlockTest extends IntegrationTestCase {

	private AuthorGridBlock $block;

	public function set_up(): void {
		parent::set_up();
		$this->block = new AuthorGridBlock();
	}

	public function test_block_name(): void {
		$this->assertSame( 'author-grid', $this->block->get_block_name() );
	}

	public function test_render_callback_returns_error_when_no_authors_selected(): void {
		$html = $this->block->render_callback( array(), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'grid', $html );
	}

	public function test_render_callback_returns_error_when_no_authors_match_role(): void {
		$user_id = $this->create_author( array( 'role' => 'author' ) );

		$html = $this->block->render_callback(
			array(
				'authorIds'  => array( $user_id ),
				'authorRole' => 'editor',
			),
			'',
			null
		);

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'No authors found', $html );
	}

	public function test_render_block_renders_authors(): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'Alice' ) ),
			$this->create_full_author( array( 'display_name' => 'Bob' ) ),
		);

		$html = $this->render_block(
			'author-grid',
			array( 'authorIds' => $ids, 'columns' => 2 )
		);

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'Alice', $html );
		$this->assertStringContainsString( 'Bob', $html );
		$this->assertStringContainsString( 'apbl-author-grid', $html );
	}

	public function test_render_block_respects_max_authors_limit(): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'Alice' ) ),
			$this->create_full_author( array( 'display_name' => 'Bob' ) ),
			$this->create_full_author( array( 'display_name' => 'Carol' ) ),
		);

		$html = $this->render_block(
			'author-grid',
			array( 'authorIds' => $ids, 'maxAuthors' => 2 )
		);

		$this->assertStringContainsString( 'Alice', $html );
		$this->assertStringContainsString( 'Bob', $html );
		$this->assertStringNotContainsString( 'Carol', $html );
	}

	public function test_render_block_filters_by_role(): void {
		$author_id = $this->create_full_author( array( 'role' => 'author', 'display_name' => 'AuthorOnly' ) );
		$editor_id = $this->create_full_author( array( 'role' => 'editor', 'display_name' => 'EditorOnly' ) );

		$html = $this->render_block(
			'author-grid',
			array(
				'authorIds'  => array( $author_id, $editor_id ),
				'authorRole' => 'editor',
			)
		);

		$this->assertStringContainsString( 'EditorOnly', $html );
		$this->assertStringNotContainsString( 'AuthorOnly', $html );
	}

	public function test_render_author_item_emits_layout_classes(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->block->render_author_item(
			$author,
			array(
				'layout'        => 'card',
				'layoutPreset'  => 'modern',
				'animationType' => 'fadeIn',
				'hoverEffect'   => 'lift',
				'enableShadow'  => true,
				'enableBorder'  => true,
				'enableRounded' => true,
			)
		);

		$this->assertStringContainsString( 'apbl-author-grid-item', $html );
		$this->assertStringContainsString( 'is-layout-card', $html );
		$this->assertStringContainsString( 'modern', $html );
		$this->assertStringContainsString( 'has-fadeIn-animation', $html );
		$this->assertStringContainsString( 'has-lift-hover', $html );
		$this->assertStringContainsString( 'has-shadow', $html );
		$this->assertStringContainsString( 'has-border', $html );
		$this->assertStringContainsString( 'is-rounded', $html );
	}

	public function test_render_author_item_includes_social_when_enabled(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->block->render_author_item(
			$author,
			array( 'showSocial' => true, 'layout' => 'card' )
		);

		$this->assertStringContainsString( 'facebook.com/test', $html );
	}
}
