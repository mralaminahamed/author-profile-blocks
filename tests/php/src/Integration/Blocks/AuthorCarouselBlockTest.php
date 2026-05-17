<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\AuthorCarouselBlock;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for AuthorCarouselBlock.
 */
class AuthorCarouselBlockTest extends IntegrationTestCase {

	private AuthorCarouselBlock $block;

	public function set_up(): void {
		parent::set_up();
		$this->block = new AuthorCarouselBlock();
		// Block-specific init registers the carousel script.
		$this->block->register_carousel_dependencies();
	}

	public function test_block_name(): void {
		$this->assertSame( 'author-carousel', $this->block->get_block_name() );
	}

	public function test_register_carousel_dependencies_registers_view_script(): void {
		$this->assertTrue( \wp_script_is( 'author-carousel-view', 'registered' ) );
	}

	public function test_register_carousel_dependencies_declares_jquery_dependency(): void {
		$script = \wp_scripts()->registered['author-carousel-view'] ?? null;
		$this->assertNotNull( $script, 'author-carousel-view script not registered' );
		$this->assertContains( 'jquery', $script->deps, 'carousel view.js must list jquery as a dependency for classic themes' );
	}

	public function test_render_callback_returns_empty_on_frontend_when_no_authors_selected(): void {
		$this->assertSame( '', $this->block->render_callback( array(), '', null ) );
	}

	public function test_render_callback_returns_empty_on_frontend_when_role_filter_excludes_all(): void {
		$id   = $this->create_author( array( 'role' => 'author' ) );
		$html = $this->block->render_callback(
			array( 'authorIds' => array( $id ), 'authorRole' => 'editor' ),
			'',
			null
		);

		$this->assertSame( '', $html );
	}

	public function test_render_callback_returns_error_in_editor_when_no_authors_selected(): void {
		$this->simulate_editor_context();
		$html = $this->block->render_callback( array(), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'carousel', $html );
	}

	public function test_render_callback_returns_error_in_editor_when_role_filter_excludes_all(): void {
		$this->simulate_editor_context();
		$id   = $this->create_author( array( 'role' => 'author' ) );
		$html = $this->block->render_callback(
			array( 'authorIds' => array( $id ), 'authorRole' => 'editor' ),
			'',
			null
		);

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'No authors found', $html );
	}

	public function test_render_block_enqueues_view_script(): void {
		$id = $this->create_full_author();

		$this->render_block( 'author-carousel', array( 'authorIds' => array( $id ) ) );

		$this->assertTrue( \wp_script_is( 'author-carousel-view', 'enqueued' ) );
	}

	public function test_render_block_outputs_carousel_markup(): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'Slide A' ) ),
			$this->create_full_author( array( 'display_name' => 'Slide B' ) ),
			$this->create_full_author( array( 'display_name' => 'Slide C' ) ),
		);

		$html = $this->render_block(
			'author-carousel',
			array(
				'authorIds'    => $ids,
				'slidesToShow' => 2,
				'autoplay'     => true,
				'showDots'     => true,
				'showArrows'   => true,
			)
		);

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'Slide A', $html );
		$this->assertStringContainsString( 'Slide B', $html );
		$this->assertStringContainsString( 'Slide C', $html );
	}

	public function test_render_block_respects_max_authors_limit(): void {
		$ids = array(
			$this->create_full_author( array( 'display_name' => 'Slide A' ) ),
			$this->create_full_author( array( 'display_name' => 'Slide B' ) ),
			$this->create_full_author( array( 'display_name' => 'Slide C' ) ),
		);

		$html = $this->render_block(
			'author-carousel',
			array( 'authorIds' => $ids, 'maxAuthors' => 2 )
		);

		$this->assertStringContainsString( 'Slide A', $html );
		$this->assertStringContainsString( 'Slide B', $html );
		$this->assertStringNotContainsString( 'Slide C', $html );
	}

	public function test_render_author_slide_includes_layout_classes(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->block->render_author_slide(
			$author,
			array(
				'layout'        => 'card',
				'layoutPreset'  => 'modern-cards',
				'animationType' => 'fadeIn',
				'hoverEffect'   => 'lift',
				'enableShadow'  => true,
				'enableBorder'  => true,
				'enableRounded' => true,
				'showSocial'    => true,
			)
		);

		$this->assertStringContainsString( 'apbl-author-carousel-item', $html );
		$this->assertStringContainsString( 'is-layout-card', $html );
		$this->assertStringContainsString( 'modern-cards', $html );
		$this->assertStringContainsString( 'has-fadeIn-animation', $html );
		$this->assertStringContainsString( 'has-lift-hover', $html );
		$this->assertStringContainsString( 'has-shadow', $html );
		$this->assertStringContainsString( 'has-border', $html );
		$this->assertStringContainsString( 'is-rounded', $html );
		$this->assertStringContainsString( 'facebook.com/test', $html );
	}
}
