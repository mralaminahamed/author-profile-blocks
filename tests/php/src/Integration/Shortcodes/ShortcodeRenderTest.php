<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Shortcodes;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests that render each shortcode end-to-end.
 *
 * Guards against the regression where shortcodes emitted empty cards because
 * they included item templates without the pre-rendered component variables
 * and fed them provider data in the wrong shape.
 */
class ShortcodeRenderTest extends IntegrationTestCase {

	/**
	 * Create a fully populated author including the core user description,
	 * which the data provider (unlike the service) reads for the bio.
	 *
	 * @param string $display_name Display name.
	 * @return int User ID.
	 */
	private function author( string $display_name ): int {
		return $this->create_full_author(
			array(
				'display_name' => $display_name,
				'description'  => 'Bio for ' . $display_name,
			)
		);
	}

	public function test_profile_shortcode_renders_single_author(): void {
		$id   = $this->author( 'Alice' );
		$html = \do_shortcode( '[apbl_profile id="' . $id . '"]' );

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'Alice', $html );
		$this->assertStringContainsString( 'apbl-author-grid-item', $html );
		$this->assertStringContainsString( 'wp-block-author-profile-blocks-author-profile', $html );
	}

	public function test_grid_shortcode_renders_authors_with_bio_and_social(): void {
		$alice = $this->author( 'Alice' );
		$bob   = $this->author( 'Bob' );

		$html = \do_shortcode( '[apbl_grid ids="' . $alice . ',' . $bob . '" columns="2"]' );

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'apbl-author-grid', $html );
		$this->assertStringContainsString( 'Alice', $html );
		$this->assertStringContainsString( 'Bob', $html );
		// Data-shape fix: description (bio) and social now reach the templates.
		$this->assertStringContainsString( 'Bio for Alice', $html );
		$this->assertStringContainsString( 'facebook.com/test', $html );
	}

	public function test_list_shortcode_renders_authors(): void {
		$id   = $this->author( 'Carol' );
		$html = \do_shortcode( '[apbl_list ids="' . $id . '"]' );

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'apbl-author-list', $html );
		$this->assertStringContainsString( 'Carol', $html );
	}

	public function test_carousel_shortcode_renders_authors(): void {
		$id   = $this->author( 'Dave' );
		$html = \do_shortcode( '[apbl_carousel ids="' . $id . '"]' );

		$this->assertNotEmpty( $html );
		$this->assertStringContainsString( 'apbl-author-carousel', $html );
		$this->assertStringContainsString( 'Dave', $html );
	}

	public function test_grid_shortcode_returns_empty_for_no_authors(): void {
		$html = \do_shortcode( '[apbl_grid ids="999999999"]' );
		$this->assertSame( '', $html );
	}
}
