<?php
namespace AuthorProfileBlocks\Test\Unit\Shortcodes;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class AuthorProfileShortcodeTest extends AuthorProfileBlocksTestCase {

	public function test_shortcode_files_exist(): void {
		$base = TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Shortcodes/';
		$this->assertFileExists( $base . 'ShortcodeRegistry.php' );
		$this->assertFileExists( $base . 'AuthorProfileShortcode.php' );
		$this->assertFileExists( $base . 'AuthorGridShortcode.php' );
		$this->assertFileExists( $base . 'AuthorListShortcode.php' );
		$this->assertFileExists( $base . 'AuthorCarouselShortcode.php' );
	}

	public function test_shortcode_tags_registered(): void {
		$files = array(
			'AuthorProfileShortcode.php'  => 'apbl_profile',
			'AuthorGridShortcode.php'     => 'apbl_grid',
			'AuthorListShortcode.php'     => 'apbl_list',
			'AuthorCarouselShortcode.php' => 'apbl_carousel',
		);
		foreach ( $files as $file => $tag ) {
			$content = file_get_contents(
				TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Shortcodes/' . $file
			);
			$this->assertStringContainsString( "'$tag'", $content, "$file missing tag $tag" );
			$this->assertStringContainsString( 'add_shortcode(', $content, "$file missing add_shortcode" );
		}
	}

	public function test_shortcodes_use_ob_start(): void {
		foreach ( array( 'AuthorProfileShortcode.php', 'AuthorGridShortcode.php', 'AuthorListShortcode.php', 'AuthorCarouselShortcode.php' ) as $file ) {
			$content = file_get_contents(
				TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Shortcodes/' . $file
			);
			$this->assertStringContainsString( 'ob_start()', $content, "$file missing ob_start" );
			$this->assertStringContainsString( 'ob_get_clean()', $content, "$file missing ob_get_clean" );
		}
	}

	public function test_registry_has_add_and_init(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Shortcodes/ShortcodeRegistry.php'
		);
		$this->assertStringContainsString( 'public function add(', $content );
		$this->assertStringContainsString( 'public function init(): void', $content );
	}
}
