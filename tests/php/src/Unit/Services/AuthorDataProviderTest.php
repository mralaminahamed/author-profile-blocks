<?php
namespace AuthorProfileBlocks\Test\Unit\Services;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class AuthorDataProviderTest extends AuthorProfileBlocksTestCase {

	public function test_provider_class_file_exists(): void {
		$this->assertFileExists(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/AuthorDataProvider.php'
		);
	}

	public function test_provider_class_structure(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/AuthorDataProvider.php'
		);
		$this->assertStringContainsString('class AuthorDataProvider', $content);
		$this->assertStringContainsString('implements Registerable', $content);
		$this->assertStringContainsString('UserMetaProvider', $content);
	}

	public function test_provider_has_required_methods(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/AuthorDataProvider.php'
		);
		$this->assertStringContainsString('public function get_author(', $content);
		$this->assertStringContainsString('public function get_authors(', $content);
		$this->assertStringContainsString('private function normalize_user(', $content);
		$this->assertStringContainsString('private function normalize_team_member(', $content);
	}

	public function test_provider_normalized_shape_has_required_keys(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/AuthorDataProvider.php'
		);
		foreach ( array( 'id', 'name', 'position', 'bio', 'avatar_url', 'socials', 'department', 'skills', 'location', 'source', 'post_count', 'joined' ) as $key ) {
			$this->assertStringContainsString( "'$key'", $content, "Missing key: $key" );
		}
	}

	public function test_provider_supports_both_sources(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/AuthorDataProvider.php'
		);
		$this->assertStringContainsString("'user'", $content);
		$this->assertStringContainsString("'team_member'", $content);
		$this->assertStringContainsString("TeamMemberPostType::POST_TYPE", $content);
	}
}
