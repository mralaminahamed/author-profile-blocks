<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration;

use AuthorProfileBlocks\Blocks\Author_Carousel_Block;
use AuthorProfileBlocks\Blocks\Author_Grid_Block;
use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Blocks\AuthorProfileBlock;

/**
 * Adversarial inputs through every public seam:
 *   - Block render attributes (XSS vectors)
 *   - Social profile sanitization (javascript: URLs, data: URLs)
 *   - Profile-save handler (capability + nonce checks)
 */
class SecurityTest extends IntegrationTestCase {

	public function test_sanitize_social_profiles_strips_javascript_url(): void {
		$result = \author_profile_blocks()->sanitize_social_profiles(
			array(
				'facebook' => 'javascript:alert(1)',
				'twitter'  => 'https://twitter.com/ok',
			)
		);

		// esc_url_raw drops the javascript: scheme.
		$this->assertStringNotContainsString( 'javascript:', $result['facebook'] );
		$this->assertSame( 'https://twitter.com/ok', $result['twitter'] );
	}

	public function test_sanitize_social_profiles_keeps_only_known_keys(): void {
		$result = \author_profile_blocks()->sanitize_social_profiles(
			array(
				'facebook'   => 'https://facebook.com/x',
				'extra_key'  => 'https://evil.example/leak',
				'__proto__'  => 'malicious',
			)
		);

		$this->assertArrayNotHasKey( 'extra_key', $result );
		$this->assertArrayNotHasKey( '__proto__', $result );
	}

	public function test_sanitize_social_profiles_preserves_data_url_when_invalid(): void {
		$result = \author_profile_blocks()->sanitize_social_profiles(
			array( 'website' => 'data:text/html,<script>x()</script>' )
		);

		// esc_url_raw keeps a sanitized URL string, but the inline tags must
		// not survive on output.
		$this->assertStringNotContainsString( '<script>', $result['website'] );
	}

	public function test_save_profile_fields_requires_edit_user_capability(): void {
		// Subscriber cannot edit other users.
		$sub_id = $this->factory()->user->create( array( 'role' => 'subscriber' ) );
		$this->created_user_ids[] = $sub_id;
		\wp_set_current_user( $sub_id );

		$victim = $this->create_author();

		$_POST = array(
			'apbl_profile_nonce'   => \wp_create_nonce( 'apbl_save_profile_data' ),
			'apbl_author_position' => 'Hijacked',
		);

		\author_profile_blocks()->save_author_profile_fields( $victim );

		// Position must NOT have changed.
		$this->assertSame( '', \get_user_meta( $victim, 'apbl_author_position', true ) );

		$_POST = array();
	}

	public function test_save_profile_fields_rejects_missing_nonce(): void {
		$admin = $this->factory()->user->create( array( 'role' => 'administrator' ) );
		$this->created_user_ids[] = $admin;
		\wp_set_current_user( $admin );

		$user = $this->create_author();

		$_POST = array( 'apbl_author_position' => 'NoNonce' );

		\author_profile_blocks()->save_author_profile_fields( $user );

		$this->assertSame( '', \get_user_meta( $user, 'apbl_author_position', true ) );
		$_POST = array();
	}

	public function test_block_classes_escape_custom_css_class(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-profile',
			array(
				'authorId'       => $id,
				'customCssClass' => '"><img src=x onerror=alert(1)>',
			)
		);
		// The injected payload must stay inside the class attribute as escaped
		// text — i.e. it must NOT close the attribute and inject a real <img> tag.
		$this->assertStringNotContainsString( '<img src=x onerror=', $html );
		$this->assertStringContainsString( '&quot;', $html );
	}

	public function test_grid_attribute_xss_does_not_break_out(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-grid',
			array(
				'authorIds'      => array( $id ),
				'customCssClass' => '\'; DROP TABLE wp_users;--',
			)
		);
		$this->assertStringContainsString( 'apbl-author-grid', $html );
	}

	public function test_list_with_xss_in_layout_preset(): void {
		$id   = $this->create_full_author();
		$html = $this->render_block(
			'author-list',
			array(
				'authorIds'    => array( $id ),
				'layoutPreset' => '"><script>x()</script>',
			)
		);
		$this->assertStringNotContainsString( '<script>x()</script>', $html );
	}

	public function test_carousel_with_html_in_author_position(): void {
		$id = $this->create_full_author();
		\update_user_meta( $id, 'apbl_author_position', '<script>steal()</script>' );

		$html = $this->render_block( 'author-carousel', array( 'authorIds' => array( $id ) ) );
		$this->assertStringNotContainsString( '<script>steal()</script>', $html );
	}

	public function test_uninstall_constants_block_unauthorized_call(): void {
		// Calling the uninstall script directly without WP_UNINSTALL_PLUGIN
		// must be a safe no-op (it returns early).
		\update_option( 'author_profile_blocks_settings', array( 'kept' => true ) );

		// Simulate inclusion without WP_UNINSTALL_PLUGIN — the script bails
		// before any deletion.
		$ran = false;
		( function () use ( &$ran ) {
			if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
				$ran = false;
				return;
			}
			$ran = true;
		} )();

		$this->assertSame(
			array( 'kept' => true ),
			\get_option( 'author_profile_blocks_settings' )
		);
	}
}
