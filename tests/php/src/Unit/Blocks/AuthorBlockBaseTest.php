<?php

namespace AuthorProfileBlocks\Test\Unit\Blocks;

use AuthorProfileBlocks\Test\AuthorProfileBlocksTestCase;

/**
 * Unit test for AuthorBlockBase.
 */
class AuthorBlockBaseTest extends AuthorProfileBlocksTestCase {

	/**
	 * Test that AuthorBlockBase has source attribute helper.
	 */
	public function test_base_block_has_source_attribute_helper(): void {
		$content = file_get_contents(
			TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/includes/Blocks/AuthorBlockBase.php'
		);
		$this->assertStringContainsString( "'source'", $content );
		$this->assertStringContainsString( "'team_member'", $content );
		$this->assertStringContainsString( 'get_source_attribute', $content );
	}
}
