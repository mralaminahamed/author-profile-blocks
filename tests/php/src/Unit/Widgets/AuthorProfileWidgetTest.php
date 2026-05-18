<?php
namespace AuthorProfileBlocks\Test\Unit\Widgets;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class AuthorProfileWidgetTest extends AuthorProfileBlocksTestCase {

	public function test_widget_file_exists(): void {
		$this->assertFileExists(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Widgets/AuthorProfileWidget.php'
		);
	}

	public function test_widget_extends_wp_widget(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Widgets/AuthorProfileWidget.php'
		);
		$this->assertStringContainsString( 'extends \WP_Widget', $content );
	}

	public function test_widget_has_required_methods(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Widgets/AuthorProfileWidget.php'
		);
		$this->assertStringContainsString( 'public function widget(', $content );
		$this->assertStringContainsString( 'public function form(', $content );
		$this->assertStringContainsString( 'public function update(', $content );
	}

	public function test_widget_uses_shortcode(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Widgets/AuthorProfileWidget.php'
		);
		$this->assertStringContainsString( 'do_shortcode(', $content );
		$this->assertStringContainsString( 'apbl_profile', $content );
	}

	public function test_widget_registered_in_main_class(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		$this->assertStringContainsString( 'AuthorProfileWidget', $content );
		$this->assertStringContainsString( 'register_widget(', $content );
	}
}
