<?php
namespace AuthorProfileBlocks\Test\Unit\PostTypes;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class TeamMemberPostTypeTest extends AuthorProfileBlocksTestCase {

    public function test_post_type_class_file_exists(): void {
        $this->assertFileExists(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/PostTypes/TeamMemberPostType.php'
        );
    }

    public function test_post_type_class_structure(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/PostTypes/TeamMemberPostType.php'
        );
        $this->assertStringContainsString('class TeamMemberPostType', $content);
        $this->assertStringContainsString("const POST_TYPE = 'apbl_team_member'", $content);
        $this->assertStringContainsString('implements Registerable', $content);
    }

    public function test_post_type_has_required_methods(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/PostTypes/TeamMemberPostType.php'
        );
        $this->assertStringContainsString('public function init(): void', $content);
        $this->assertStringContainsString('register_post_type(', $content);
    }

    public function test_post_type_supports_expected_features(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/PostTypes/TeamMemberPostType.php'
        );
        $this->assertStringContainsString("'thumbnail'", $content);
        $this->assertStringContainsString("'editor'", $content);
        $this->assertStringContainsString("'title'", $content);
        $this->assertStringContainsString("'menu_order'", $content);
        $this->assertStringContainsString("'show_in_rest' => true", $content);
    }

    public function test_post_type_registers_meta_fields(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/PostTypes/TeamMemberPostType.php'
        );
        $this->assertStringContainsString('apbl_tm_position', $content);
        $this->assertStringContainsString('apbl_tm_social_profiles', $content);
        $this->assertStringContainsString('register_post_meta(', $content);
    }
}
