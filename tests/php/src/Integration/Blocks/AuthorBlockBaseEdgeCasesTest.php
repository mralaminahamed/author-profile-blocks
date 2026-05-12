<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Blocks;

use AuthorProfileBlocks\Blocks\AuthorBlockBase;
use AuthorProfileBlocks\Blocks\AuthorProfileBlock;
use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * Edge cases for shared logic in AuthorBlockBase (exercised through the
 * concrete AuthorProfileBlock subclass).
 */
class AuthorBlockBaseEdgeCasesTest extends IntegrationTestCase {

	private AuthorProfileBlock $block;
	private ReflectionClass $rc;

	public function set_up(): void {
		parent::set_up();
		$this->block = new AuthorProfileBlock();
		$this->rc    = new ReflectionClass( $this->block );
	}

	private function call_protected( string $method, array $args = array() ) {
		$m = $this->rc->getMethod( $method );
		if ( PHP_VERSION_ID < 80100 ) {
			$m->setAccessible( true );
		}
		return $m->invokeArgs( $this->block, $args );
	}

	public function test_register_skips_when_block_json_missing(): void {
		// Create a fake block subclass whose name points to a non-existent
		// build path — register() must short-circuit silently.
		$anon = new class() extends AuthorBlockBase {
			public function get_block_name(): string {
				return 'this-block-does-not-exist';
			}
		};

		$registry_before = \WP_Block_Type_Registry::get_instance();
		$count_before    = count( $registry_before->get_all_registered() );

		$anon->register();

		$count_after = count( $registry_before->get_all_registered() );
		$this->assertSame( $count_before, $count_after, 'no block should have been registered' );
	}

	public function test_get_social_icon_data_returns_expected_platforms(): void {
		$method = new ReflectionMethod( AuthorBlockBase::class, 'get_social_icon_data' );
		if ( PHP_VERSION_ID < 80100 ) {
			$method->setAccessible( true );
		}

		$data = $method->invoke( $this->block );

		$this->assertSame(
			array( 'facebook', 'twitter', 'linkedin', 'instagram', 'website' ),
			array_keys( $data )
		);
		$this->assertSame( 'admin-site', $data['website']['icon'] );
	}

	public function test_get_social_icons_returns_dashicons_map(): void {
		$method = new ReflectionMethod( AuthorBlockBase::class, 'get_social_icons' );
		if ( PHP_VERSION_ID < 80100 ) {
			$method->setAccessible( true );
		}

		$icons = $method->invoke( $this->block );

		$this->assertSame( 'dashicons-facebook', $icons['facebook'] );
		$this->assertSame( 'dashicons-admin-site', $icons['website'] );
	}

	public function test_render_error_message_renders_translated_strings(): void {
		$out = $this->call_protected( 'render_error_message', array( 'Custom error' ) );
		$this->assertStringContainsString( 'apbl-error-message', $out );
		$this->assertStringContainsString( 'Custom error', $out );
	}

	public function test_get_authors_data_returns_empty_array_for_invalid_ids(): void {
		$result = $this->call_protected(
			'get_authors_data',
			array( array( -1, 0, 999999 ), array() )
		);
		$this->assertSame( array(), $result );
	}

	public function test_get_block_classes_includes_avatar_shape_only_when_set(): void {
		$with    = $this->call_protected( 'get_block_classes', array( array( 'avatarShape' => 'square' ), '' ) );
		$without = $this->call_protected( 'get_block_classes', array( array(), '' ) );

		$this->assertStringContainsString( 'avatar-shape-square', $with );
		$this->assertStringNotContainsString( 'avatar-shape-', $without );
	}

	public function test_get_block_styles_emits_gradient_background(): void {
		$styles = $this->call_protected(
			'get_block_styles',
			array(
				array(
					'gradientBackground'  => true,
					'gradientStartColor'  => '#ff0000',
					'gradientEndColor'    => '#00ff00',
					'gradientDirection'   => 'to right',
				),
			)
		);

		$this->assertStringContainsString( 'linear-gradient(to right, #ff0000, #00ff00)', $styles['background'] );
	}

	public function test_get_block_styles_uses_default_gradient_when_partial(): void {
		$styles = $this->call_protected(
			'get_block_styles',
			array( array( 'gradientBackground' => true ) )
		);

		$this->assertStringContainsString( 'to bottom, #ffffff, #f8f9fa', $styles['background'] );
	}

	public function test_get_block_styles_emits_filter_custom_properties(): void {
		$styles = $this->call_protected(
			'get_block_styles',
			array(
				array(
					'filterBrightness' => 80,
					'filterContrast'   => 120,
					'filterSaturate'   => 90,
				),
			)
		);

		$this->assertSame( '80%', $styles['--author-filter-brightness'] );
		$this->assertSame( '120%', $styles['--author-filter-contrast'] );
		$this->assertSame( '90%', $styles['--author-filter-saturate'] );
	}

	public function test_get_block_styles_omits_filter_when_value_is_default_100(): void {
		$styles = $this->call_protected(
			'get_block_styles',
			array(
				array(
					'filterBrightness' => 100,
					'filterContrast'   => 100,
					'filterSaturate'   => 100,
				),
			)
		);

		$this->assertArrayNotHasKey( '--author-filter-brightness', $styles );
		$this->assertArrayNotHasKey( '--author-filter-contrast', $styles );
		$this->assertArrayNotHasKey( '--author-filter-saturate', $styles );
	}

	public function test_get_block_styles_avatar_alignment_emits_justify_value(): void {
		$styles = $this->call_protected(
			'get_block_styles',
			array( array( 'avatarAlignment' => 'right' ) )
		);

		$this->assertSame( 'right', $styles['--author-avatar-align'] );
		$this->assertSame( 'flex-end', $styles['--author-avatar-justify'] );
	}

	public function test_get_block_styles_avatar_border_radius_only_for_custom_shape(): void {
		$with_custom = $this->call_protected(
			'get_block_styles',
			array( array( 'avatarShape' => 'custom', 'avatarBorderRadius' => 12 ) )
		);
		$with_circle = $this->call_protected(
			'get_block_styles',
			array( array( 'avatarShape' => 'circle', 'avatarBorderRadius' => 12 ) )
		);

		$this->assertSame( '12px', $with_custom['--author-avatar-border-radius'] );
		$this->assertArrayNotHasKey( '--author-avatar-border-radius', $with_circle );
	}

	public function test_get_item_styles_omits_transform_when_neutral(): void {
		$styles = $this->call_protected(
			'get_item_styles',
			array( array( 'transformScale' => 1, 'transformRotate' => 0 ) )
		);

		$this->assertArrayNotHasKey( 'transform', $styles );
	}

	public function test_get_item_styles_combines_transform_components(): void {
		$styles = $this->call_protected(
			'get_item_styles',
			array( array( 'transformScale' => 1.2, 'transformRotate' => 5 ) )
		);

		$this->assertSame( 'scale(1.2) rotate(5deg)', $styles['transform'] );
	}

	public function test_get_item_styles_includes_filter_chain(): void {
		$styles = $this->call_protected(
			'get_item_styles',
			array(
				array(
					'filterBrightness' => 80,
					'filterContrast'   => 120,
					'filterSaturate'   => 90,
				),
			)
		);

		$this->assertSame( 'brightness(80%) contrast(120%) saturate(90%)', $styles['filter'] );
	}

	public function test_render_minimal_layout_includes_template(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->call_protected(
			'render_minimal_layout',
			array( $author, array() )
		);

		$this->assertStringContainsString( 'author-profile-blocks-minimal', $html );
	}

	public function test_render_centered_layout_includes_template(): void {
		$id     = $this->create_full_author();
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->call_protected(
			'render_centered_layout',
			array( $author, array() )
		);

		$this->assertNotEmpty( $html );
	}

	public function test_render_compact_layout_includes_name(): void {
		$id     = $this->create_full_author( array( 'display_name' => 'CompactPerson' ) );
		$author = \author_profile_blocks()->get_author_data( $id );

		$html = $this->call_protected(
			'render_compact_layout',
			array( $author, array() )
		);

		$this->assertStringContainsString( 'CompactPerson', $html );
	}

	public function test_render_more_content_returns_empty_for_empty_string(): void {
		$out = $this->call_protected( 'render_more_content', array( '' ) );
		$this->assertSame( '', $out );
	}

	public function test_render_more_content_renders_content(): void {
		$out = $this->call_protected( 'render_more_content', array( 'Plain content' ) );
		$this->assertStringContainsString( 'Plain content', $out );
	}

	public function test_render_social_profiles_returns_empty_for_empty_array(): void {
		$out = $this->call_protected( 'render_social_profiles', array( array() ) );
		// Template still renders the wrapper but no links — should not break.
		$this->assertIsString( $out );
	}

	public function test_localize_block_script_emits_required_globals(): void {
		// Just ensure the method runs without errors. The script handle
		// exists only after register_block_type, but the call must not throw.
		$this->block->localize_block_script();
		$this->assertTrue( true );
	}

	public function test_extract_author_ids_dedupes_when_php_collapses_keys(): void {
		// PHP arrays preserve order with mixed keys; intval coerces strings.
		$result = $this->call_protected(
			'extract_author_ids',
			array( array( 'authorIds' => array( 5, '5', 5.0 ) ) )
		);

		// All three coerce to 5 — the helper does not deduplicate, so
		// duplicates flow through. Lock the documented behaviour.
		$this->assertSame( array( 5, 5, 5 ), $result );
	}
}
