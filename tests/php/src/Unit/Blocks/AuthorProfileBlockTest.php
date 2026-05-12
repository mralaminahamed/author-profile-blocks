<?php

namespace AuthorProfileBlocks\Test\Unit\Blocks;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

/**
 * Unit test for AuthorProfileBlock.
 */
class AuthorProfileBlockTest extends AuthorProfileBlocksTestCase {

    /**
     * Test that AuthorProfileBlock class file exists.
     */
    public function test_author_profile_block_class_file_exists() {
        $this->assertFileExists(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');
    }

    /**
     * Test that AuthorProfileBlock class contains expected structure.
     */
    public function test_author_profile_block_class_structure() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');

        $this->assertStringContainsString('class AuthorProfileBlock extends AuthorBlockBase', $class_content);
    }

    /**
     * Test that AuthorProfileBlock class contains expected methods.
     */
    public function test_author_profile_block_has_expected_methods() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');

        $this->assertStringContainsString('public function get_block_name(): string', $class_content);
        $this->assertStringContainsString('protected function block_specific_init(): void', $class_content);
        $this->assertStringContainsString('protected function get_render_callback(): ?callable', $class_content);
        $this->assertStringContainsString('public function render_callback(', $class_content);
    }

    /**
     * Test that get_block_name method returns correct value.
     */
    public function test_get_block_name_method_returns_correct_value() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');

        $this->assertStringContainsString("return 'author-profile';", $class_content);
    }

    /**
     * Test that class contains block-specific initialization.
     */
    public function test_class_contains_block_specific_initialization() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');

        $this->assertStringContainsString("add_action( 'enqueue_block_editor_assets', array( \$this, 'localize_block_script' ) );", $class_content);
    }

    /**
     * Test that class contains social icons data usage.
     */
    public function test_class_contains_social_icons_usage() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorBlockBase.php');

        $this->assertStringContainsString("'socialIcons' => \$this->get_social_icon_data()", $class_content);
    }

    /**
     * Test that class contains proper namespace and imports.
     */
    public function test_class_has_proper_namespace_and_imports() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorProfileBlock.php');

        $this->assertStringContainsString('namespace AuthorProfileBlocks\Blocks;', $class_content);
        $this->assertStringContainsString('use AuthorProfileBlocks\Blocks\AuthorBlockBase;', $class_content);
        $this->assertStringContainsString('use WP_Block;', $class_content);
    }
}