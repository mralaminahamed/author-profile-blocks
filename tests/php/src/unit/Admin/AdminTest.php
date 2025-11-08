<?php

namespace AuthorProfileBlocks\Test\Admin;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

/**
 * Test Admin functionality.
 */
class AdminTest extends AuthorProfileBlocksTestCase {

    /**
     * Test that admin class file exists.
     */
    public function test_admin_class_file_exists() {
        $this->assertFileExists(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Admin/Admin.php');
    }

    /**
     * Test that admin class contains expected structure.
     */
    public function test_admin_class_structure() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Admin/Admin.php');

        $this->assertStringContainsString('class Admin', $class_content);
    }

    /**
     * Test admin class has expected methods.
     */
    public function test_admin_class_has_expected_methods() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Admin/Admin.php');

        $this->assertStringContainsString('private function init(): void', $class_content);
        $this->assertStringContainsString('public function enqueue_scripts(', $class_content);
        $this->assertStringContainsString('public function add_menu_pages(): void', $class_content);
        $this->assertStringContainsString('public function settings_page(): void', $class_content);
    }

    /**
     * Test admin class contains WordPress function calls.
     */
    public function test_admin_class_contains_wordpress_function_calls() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Admin/Admin.php');

        $this->assertStringContainsString('add_action(', $class_content);
        $this->assertStringContainsString('wp_enqueue_style(', $class_content);
        $this->assertStringContainsString('add_options_page(', $class_content);
        $this->assertStringContainsString('settings_fields(', $class_content);
    }

    /**
     * Test admin class has proper constructor.
     */
    public function test_admin_class_has_constructor() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Admin/Admin.php');

        $this->assertStringContainsString('public function __construct()', $class_content);
    }


}
