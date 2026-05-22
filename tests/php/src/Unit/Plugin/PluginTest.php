<?php
namespace AuthorProfileBlocks\Test\Unit\Plugin;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class PluginTest extends AuthorProfileBlocksTestCase {

	public function test_main_class_wires_department_taxonomy(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		$this->assertStringContainsString( 'DepartmentTaxonomy', $content );
		$this->assertStringContainsString( 'use AuthorProfileBlocks\\Taxonomies\\DepartmentTaxonomy', $content );
	}

	public function test_main_class_wires_team_member_post_type(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		$this->assertStringContainsString( 'TeamMemberPostType', $content );
		$this->assertStringContainsString( 'use AuthorProfileBlocks\\PostTypes\\TeamMemberPostType', $content );
	}

	public function test_main_class_wires_author_data_provider(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/class-author-profile-blocks.php'
		);
		$this->assertStringContainsString( 'AuthorDataProvider', $content );
		$this->assertStringContainsString( 'use AuthorProfileBlocks\\Services\\AuthorDataProvider', $content );
	}
}
