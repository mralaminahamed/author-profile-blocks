<?php
namespace AuthorProfileBlocks\Test\Unit\Core;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class UserMetaProviderTest extends AuthorProfileBlocksTestCase {

	public function test_new_meta_fields_registered_in_main_class(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		$this->assertStringContainsString('apbl_department', $content);
		$this->assertStringContainsString('apbl_skills', $content);
		$this->assertStringContainsString('apbl_location', $content);
		$this->assertStringContainsString('apbl_phone', $content);
		$this->assertStringContainsString('apbl_availability', $content);
		$this->assertStringContainsString('apbl_website_label', $content);
	}

	public function test_new_meta_fields_shown_in_rest(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		// Count show_in_rest occurrences — existing 4 + new 6 = at least 10
		$this->assertGreaterThanOrEqual(
			10,
			substr_count( $content, "'show_in_rest'" )
		);
	}
}
