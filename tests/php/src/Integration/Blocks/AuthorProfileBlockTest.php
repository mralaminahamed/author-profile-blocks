<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\AuthorProfileBlock;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for AuthorProfileBlock.
 *
 * Direct render_callback() tests use error paths (which don't call
 * get_block_wrapper_attributes()). Happy-path tests go through do_blocks()
 * via render_block() so WP_Block context is available.
 */
class AuthorProfileBlockTest extends IntegrationTestCase {

	private AuthorProfileBlock $block;

	public function set_up(): void {
		parent::set_up();
		$this->block = new AuthorProfileBlock();
	}

	public function test_block_name(): void {
		$this->assertSame( 'author-profile', $this->block->get_block_name() );
	}

	public function test_render_callback_returns_empty_on_frontend_when_author_id_missing(): void {
		$html = $this->block->render_callback( array(), '', null );

		$this->assertSame( '', $html );
	}

	public function test_render_callback_returns_empty_on_frontend_when_author_id_zero(): void {
		$html = $this->block->render_callback( array( 'authorId' => 0 ), '', null );

		$this->assertSame( '', $html );
	}

	public function test_render_callback_returns_empty_on_frontend_when_author_not_found(): void {
		$html = $this->block->render_callback( array( 'authorId' => 999999 ), '', null );

		$this->assertSame( '', $html );
	}

	public function test_render_callback_returns_error_in_editor_when_author_id_missing(): void {
		$this->simulate_editor_context();
		$html = $this->block->render_callback( array(), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'Please select an author', $html );
	}

	public function test_render_callback_returns_error_in_editor_when_author_not_found(): void {
		$this->simulate_editor_context();
		$html = $this->block->render_callback( array( 'authorId' => 999999 ), '', null );

		$this->assertStringContainsString( 'apbl-error-message', $html );
		$this->assertStringContainsString( 'Author not found', $html );
	}

	public function test_render_block_outputs_author_name_and_position(): void {
		$user_id = $this->create_full_author( array( 'display_name' => 'Jane Doe' ) );

		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'        => $user_id,
				'showImage'       => true,
				'showDescription' => true,
				'contentOrder'    => 'image-content',
			)
		);

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'Jane Doe', $html );
		$this->assertStringContainsString( 'Senior Editor', $html );
	}

	public function test_render_block_includes_social_links_when_enabled(): void {
		$user_id = $this->create_full_author();

		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'          => $user_id,
				'showSocialLinks'   => true,
				'socialLinksToShow' => array( 'facebook', 'twitter' ),
				'contentOrder'      => 'image-content',
			)
		);

		$this->assertStringContainsString( 'facebook.com/test', $html );
	}

	public function test_render_block_includes_more_content_when_enabled(): void {
		$user_id = $this->create_full_author();

		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'        => $user_id,
				'showMoreContent' => true,
				'moreContent'     => '<p>Extra <em>content</em></p>',
				'contentOrder'    => 'image-content',
			)
		);

		$this->assertStringContainsString( 'Extra', $html );
	}

	public function test_render_block_supports_each_content_order(): void {
		$user_id = $this->create_full_author();

		foreach ( array( 'image-content', 'content-image', 'image-top', 'content-top' ) as $order ) {
			$html = $this->render_block(
				'author-profile',
				array( 'authorId' => $user_id, 'contentOrder' => $order )
			);

			$this->assertNotEmpty( $html, "contentOrder={$order} produced empty output" );
			$this->assertStringNotContainsString( 'apbl-error-message', $html );
		}
	}

	public function test_block_registered_with_editor_assets_hook(): void {
		$this->assertNotFalse( \has_action( 'enqueue_block_editor_assets' ) );
	}
}
