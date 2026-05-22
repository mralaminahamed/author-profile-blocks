<?php
namespace AuthorProfileBlocks\Test\Unit\Taxonomies;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

class DepartmentTaxonomyTest extends AuthorProfileBlocksTestCase {

    public function test_taxonomy_class_file_exists(): void {
        $this->assertFileExists(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Taxonomies/DepartmentTaxonomy.php'
        );
    }

    public function test_taxonomy_class_structure(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Taxonomies/DepartmentTaxonomy.php'
        );
        $this->assertStringContainsString('class DepartmentTaxonomy', $content);
        $this->assertStringContainsString("const TAXONOMY = 'apbl_department'", $content);
        $this->assertStringContainsString('implements Registerable', $content);
    }

    public function test_taxonomy_has_required_methods(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Taxonomies/DepartmentTaxonomy.php'
        );
        $this->assertStringContainsString('public function init(): void', $content);
        $this->assertStringContainsString('register_taxonomy(', $content);
    }

    public function test_taxonomy_registers_for_both_object_types(): void {
        $content = file_get_contents(
            TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Taxonomies/DepartmentTaxonomy.php'
        );
        $this->assertStringContainsString("'apbl_team_member'", $content);
        $this->assertStringContainsString("'user'", $content);
    }
}
