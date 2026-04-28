<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;
use ReflectionClass;

/**
 * Integration tests for shared logic in Author_Block_Base via Author_Profile_Block.
 *
 * Author_Block_Base is abstract — exercise its protected helpers through a
 * concrete subclass and reflection, since they're shared infrastructure for
 * every block.
 */
class AuthorBlockBaseTest extends IntegrationTestCase {

	private Author_Profile_Block $block;
	private ReflectionClass $rc;

	public function set_up(): void {
		parent::set_up();
		$this->block = new Author_Profile_Block();
		$this->rc    = new ReflectionClass( $this->block );
	}

	private function call_protected( string $method, array $args = array() ) {
		$m = $this->rc->getMethod( $method );
		if ( PHP_VERSION_ID < 80100 ) {
			$m->setAccessible( true );
		}
		return $m->invokeArgs( $this->block, $args );
	}

	public function test_extract_author_ids_casts_to_integers(): void {
		$result = $this->call_protected(
			'extract_author_ids',
			array( array( 'authorIds' => array( '5', '10', 'bogus' ) ) )
		);

		$this->assertSame( array( 5, 10, 0 ), $result );
	}

	public function test_extract_author_ids_returns_empty_when_missing(): void {
		$this->assertSame( array(), $this->call_protected( 'extract_author_ids', array( array() ) ) );
	}

	public function test_extract_author_roles_wraps_single_role_in_array(): void {
		$this->assertSame(
			array( 'editor' ),
			$this->call_protected( 'extract_author_roles', array( array( 'authorRole' => 'editor' ) ) )
		);
	}

	public function test_extract_author_roles_returns_empty_when_missing(): void {
		$this->assertSame( array(), $this->call_protected( 'extract_author_roles', array( array() ) ) );
	}

	public function test_extract_max_authors_returns_zero_when_missing(): void {
		$this->assertSame( 0, $this->call_protected( 'extract_max_authors', array( array() ) ) );
	}

	public function test_extract_max_authors_casts_to_int(): void {
		$this->assertSame( 7, $this->call_protected( 'extract_max_authors', array( array( 'maxAuthors' => '7' ) ) ) );
	}

	public function test_apply_author_limit_truncates(): void {
		$authors = array_map( static fn( $i ) => array( 'id' => $i ), range( 1, 5 ) );
		$result  = $this->call_protected( 'apply_author_limit', array( $authors, 2 ) );

		$this->assertCount( 2, $result );
	}

	public function test_apply_author_limit_returns_all_when_zero(): void {
		$authors = array_map( static fn( $i ) => array( 'id' => $i ), range( 1, 5 ) );
		$result  = $this->call_protected( 'apply_author_limit', array( $authors, 0 ) );

		$this->assertCount( 5, $result );
	}

	public function test_generate_cache_key_is_deterministic_regardless_of_id_order(): void {
		$key_a = $this->call_protected( 'generate_cache_key', array( array( 1, 2, 3 ), array( 'a' => 1 ) ) );
		$key_b = $this->call_protected( 'generate_cache_key', array( array( 3, 2, 1 ), array( 'a' => 1 ) ) );

		$this->assertSame( $key_a, $key_b );
	}

	public function test_generate_cache_key_differs_with_different_attributes(): void {
		$key_a = $this->call_protected( 'generate_cache_key', array( array( 1 ), array( 'a' => 1 ) ) );
		$key_b = $this->call_protected( 'generate_cache_key', array( array( 1 ), array( 'a' => 2 ) ) );

		$this->assertNotSame( $key_a, $key_b );
	}

	public function test_render_cache_round_trip(): void {
		$key = 'demo-key';
		$this->assertNull( $this->call_protected( 'get_cached_render', array( $key ) ) );

		$this->call_protected( 'set_cached_render', array( $key, '<div>cached</div>' ) );
		$this->assertSame( '<div>cached</div>', $this->call_protected( 'get_cached_render', array( $key ) ) );
	}

	public function test_get_block_classes_combines_attributes(): void {
		$result = $this->call_protected(
			'get_block_classes',
			array(
				array(
					'textAlign'      => 'center',
					'animationType'  => 'fadeIn',
					'hoverEffect'    => 'lift',
					'displayStyle'   => 'compact',
					'avatarShape'    => 'circle',
					'showImage'      => true,
					'enableShadow'   => true,
					'customCssClass' => 'my-custom-class',
				),
				'profile',
			)
		);

		$this->assertStringContainsString( 'has-text-align-center', $result );
		$this->assertStringContainsString( 'has-fadeIn-animation', $result );
		$this->assertStringContainsString( 'has-lift-hover', $result );
		$this->assertStringContainsString( 'is-style-compact', $result );
		$this->assertStringContainsString( 'avatar-shape-circle', $result );
		$this->assertStringContainsString( 'has-author-image', $result );
		$this->assertStringContainsString( 'has-shadow', $result );
		$this->assertStringContainsString( 'my-custom-class', $result );
		$this->assertStringContainsString( 'is-block-profile', $result );
	}

	public function test_get_block_classes_omits_none_animation(): void {
		$result = $this->call_protected(
			'get_block_classes',
			array( array( 'animationType' => 'none', 'hoverEffect' => 'none' ), 'grid' )
		);

		$this->assertStringNotContainsString( 'animation', $result );
		$this->assertStringNotContainsString( 'hover', $result );
	}

	public function test_get_block_styles_emits_css_custom_properties(): void {
		$result = $this->call_protected(
			'get_block_styles',
			array(
				array(
					'backgroundColor'   => '#ff0000',
					'padding'           => 24,
					'avatarSize'        => 80,
					'animationDuration' => 500,
					'sectionSpacing'    => 32,
					'nameColor'         => '#222',
				),
			)
		);

		$this->assertSame( '#ff0000', $result['background-color'] );
		$this->assertSame( '24px', $result['padding'] );
		$this->assertSame( '80px', $result['--author-avatar-size'] );
		$this->assertSame( '500ms', $result['--author-animation-duration'] );
		$this->assertSame( '32px', $result['--author-section-spacing'] );
		$this->assertSame( '#222', $result['--author-name-color'] );
	}

	public function test_get_block_styles_emits_box_shadow_with_defaults(): void {
		$result = $this->call_protected(
			'get_block_styles',
			array( array( 'boxShadow' => true ) )
		);

		$this->assertSame( '0px 4px 8px 0px rgba(0,0,0,0.2)', $result['box-shadow'] );
	}

	public function test_get_styles_string_joins_pairs(): void {
		$result = $this->call_protected(
			'get_styles_string',
			array( array( 'color' => 'red', 'padding' => '10px' ) )
		);

		$this->assertSame( 'color: red; padding: 10px', $result );
	}

	public function test_get_styles_string_returns_empty_for_empty_array(): void {
		$this->assertSame( '', $this->call_protected( 'get_styles_string', array( array() ) ) );
	}

	public function test_render_error_message_escapes_message(): void {
		$result = $this->call_protected( 'render_error_message', array( '<script>alert(1)</script>' ) );

		$this->assertStringContainsString( 'apbl-error-message', $result );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( '&lt;script&gt;', $result );
	}

	public function test_filter_rendered_output_applies_block_specific_filter(): void {
		\add_filter(
			'author_profile_blocks_rendered_author_profile',
			static fn( $content ) => $content . '::specific'
		);
		\add_filter(
			'author_profile_blocks_rendered_block',
			static fn( $content ) => $content . '::general'
		);

		$out = $this->block->filter_rendered_output( 'BASE', array( 'attributes' => array() ) );
		$this->assertSame( 'BASE::specific::general', $out );
	}

	public function test_get_authors_data_filters_by_role(): void {
		$author_id = $this->create_full_author( array( 'role' => 'author' ) );
		$editor_id = $this->create_author( array( 'role' => 'editor' ) );

		$result = $this->call_protected(
			'get_authors_data',
			array( array( $author_id, $editor_id ), array( 'editor' ) )
		);

		$ids = array_map( static fn( $a ) => $a['id'], $result );
		$this->assertContains( $editor_id, $ids );
		$this->assertNotContains( $author_id, $ids );
	}

	public function test_get_authors_data_returns_empty_when_no_ids(): void {
		$this->assertSame(
			array(),
			$this->call_protected( 'get_authors_data', array( array(), array() ) )
		);
	}
}
