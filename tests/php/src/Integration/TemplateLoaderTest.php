<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration;

/**
 * Integration tests for the template-loader API on the main plugin class.
 *
 * Covers locate_template / get_template / get_template_part / get_template_html
 * and their filter contract.
 */
class TemplateLoaderTest extends IntegrationTestCase {

	public function test_locate_template_returns_plugin_default_when_theme_missing(): void {
		$path = \author_profile_blocks()->locate_template( 'blocks/author-grid/grid.php' );

		$this->assertStringEndsWith( 'templates/blocks/author-grid/grid.php', $path );
		$this->assertFileExists( $path );
	}

	public function test_locate_template_handles_non_existent_template(): void {
		$path = \author_profile_blocks()->locate_template( 'no/such/template.php' );

		// Returns the would-be plugin path even when the file does not exist.
		$this->assertStringEndsWith( 'templates/no/such/template.php', $path );
		$this->assertFileDoesNotExist( $path );
	}

	public function test_locate_template_filter_can_redirect_path(): void {
		\add_filter(
			'author_profile_blocks_locate_template',
			static function () {
				return '/redirected/path.php';
			}
		);

		$this->assertSame(
			'/redirected/path.php',
			\author_profile_blocks()->locate_template( 'blocks/author-grid/grid.php' )
		);
	}

	public function test_get_template_part_emits_no_output_for_missing_part(): void {
		ob_start();
		\author_profile_blocks()->get_template_part( 'this-does-not-exist' );
		$out = ob_get_clean();

		$this->assertSame( '', $out );
	}

	public function test_get_template_html_returns_empty_for_unknown_template(): void {
		$out = \author_profile_blocks()->get_template_html( 'no/such/template.php' );

		// Template doesn't exist — locate_template returns the would-be path,
		// get_template tries to include it, and the include either fails
		// silently or warns. The buffered output should still be empty.
		$this->assertSame( '', $out );
	}

	public function test_get_template_html_renders_with_args(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$out = \author_profile_blocks()->get_template_html(
			'blocks/components/author-name.php',
			array( 'author' => $author, 'attributes' => array() )
		);

		$this->assertStringContainsString( $author['title'], $out );
	}

	public function test_get_template_filter_can_swap_template_path(): void {
		$called = false;
		\add_filter(
			'author_profile_blocks_get_template',
			static function ( $template ) use ( &$called ) {
				$called = true;
				return $template;
			}
		);

		\author_profile_blocks()->get_template_html(
			'blocks/components/author-name.php',
			array( 'author' => array( 'title' => 'X' ), 'attributes' => array() )
		);

		$this->assertTrue( $called );
	}

	public function test_before_and_after_template_part_actions_fire(): void {
		$before = 0;
		$after  = 0;
		\add_action(
			'author_profile_blocks_before_template_part',
			static function () use ( &$before ) {
				$before++;
			}
		);
		\add_action(
			'author_profile_blocks_after_template_part',
			static function () use ( &$after ) {
				$after++;
			}
		);

		\author_profile_blocks()->get_template_html(
			'blocks/components/author-name.php',
			array( 'author' => array( 'title' => 'X' ), 'attributes' => array() )
		);

		$this->assertGreaterThan( 0, $before );
		$this->assertGreaterThan( 0, $after );
	}
}
