<?php

namespace AuthorProfileBlocks\Test\Unit\Services;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

/**
 * Test Author Profile Service functionality.
 */
class AuthorProfileServiceTest extends AuthorProfileBlocksTestCase {

    /**
     * Test that service class file exists.
     */
    public function test_service_class_file_exists() {
        $this->assertFileExists(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');
    }

    /**
     * Test that service class contains expected structure.
     */
    public function test_service_class_structure() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('class Author_Profile_Service', $class_content);
        $this->assertStringContainsString('private User_Meta_Provider $meta_provider;', $class_content);
        $this->assertStringContainsString('private array $author_cache = array();', $class_content);
    }

    /**
     * Test service has expected methods.
     */
    public function test_service_has_expected_methods() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('public function init(): void', $class_content);
        $this->assertStringContainsString('public function register_rest_fields(): void', $class_content);
        $this->assertStringContainsString('public function get_author_data(', $class_content);
        $this->assertStringContainsString('public function get_authors(', $class_content);
        $this->assertStringContainsString('public function clear_cache(', $class_content);
    }

    /**
     * Test service contains expected WordPress function calls.
     */
    public function test_service_contains_wordpress_function_calls() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('register_rest_field(', $class_content);
        $this->assertStringContainsString('get_userdata(', $class_content);
        $this->assertStringContainsString('wp_cache_get(', $class_content);
        $this->assertStringContainsString('wp_cache_set(', $class_content);
    }

    /**
     * Test service has proper constructor.
     */
    public function test_service_has_constructor() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('public function __construct(', $class_content);
        $this->assertStringContainsString('User_Meta_Provider $meta_provider', $class_content);
    }

    /**
     * Test service has social profiles method.
     */
    public function test_service_has_social_profiles_method() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('public function get_social_profiles(', $class_content);
    }

    /**
     * Test service has cache-related methods.
     */
    public function test_service_has_cache_methods() {
        $class_content = file_get_contents(TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Services/Author_Profile_Service.php');

        $this->assertStringContainsString('public function clear_user_cache(', $class_content);
        $this->assertStringContainsString('public function clear_cache(', $class_content);
    }


}