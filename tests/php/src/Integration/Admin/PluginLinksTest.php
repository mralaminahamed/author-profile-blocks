<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Admin;

use AuthorProfileBlocks\Admin\PluginLinks;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for PluginLinks (action links + row meta).
 */
class PluginLinksTest extends IntegrationTestCase {

	public function test_add_action_links_prepends_settings_link(): void {
		$pl    = new PluginLinks();
		$links = $pl->add_action_links( array( '<a href="#">Existing</a>' ) );

		$this->assertCount( 2, $links );
		$this->assertStringContainsString( 'Settings', $links[0] );
		$this->assertStringContainsString( 'page=author-profile-blocks', $links[0] );
		$this->assertStringContainsString( 'options-general.php', $links[0] );
	}

	public function test_add_action_links_keeps_existing_links(): void {
		$pl    = new PluginLinks();
		$links = $pl->add_action_links(
			array(
				'<a href="#a">A</a>',
				'<a href="#b">B</a>',
			)
		);

		$this->assertStringContainsString( 'A', $links[1] );
		$this->assertStringContainsString( 'B', $links[2] );
	}

	public function test_add_row_meta_appends_for_matching_plugin_file(): void {
		$pl       = new PluginLinks();
		$basename = \plugin_basename( APBL_PLUGIN_FILE );

		$links = $pl->add_row_meta( array( 'existing' ), $basename );

		$this->assertCount( 3, $links );
		$this->assertStringContainsString( 'Documentation', $links[1] );
		$this->assertStringContainsString( 'GitHub', $links[2] );
		$this->assertStringContainsString( 'github.com/mralaminahamed/author-profile-blocks', $links[1] );
	}

	public function test_add_row_meta_returns_unchanged_for_other_plugin(): void {
		$pl     = new PluginLinks();
		$input  = array( 'a', 'b' );
		$result = $pl->add_row_meta( $input, 'some-other-plugin/some-other-plugin.php' );

		$this->assertSame( $input, $result );
	}

	public function test_constructor_registers_filters(): void {
		new PluginLinks();
		$basename = \plugin_basename( APBL_PLUGIN_FILE );

		$this->assertNotFalse( \has_filter( 'plugin_action_links_' . $basename ) );
		$this->assertNotFalse( \has_filter( 'plugin_row_meta' ) );
	}
}
