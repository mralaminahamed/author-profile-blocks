<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Test\Integration\Plugin;

use AuthorProfileBlocks\Test\Integration\IntegrationTestCase;

/**
 * Integration tests for the plugin settings store on the main class
 * (get_settings / get_setting / activation defaults / filters).
 */
class SettingsTest extends IntegrationTestCase {

	public function test_activation_creates_default_settings_when_unset(): void {
		\delete_option( 'author_profile_blocks_settings' );
		\delete_option( 'author_profile_blocks_activated' );

		\author_profile_blocks()->activate();

		$settings = \get_option( 'author_profile_blocks_settings' );
		$this->assertIsArray( $settings );
		$this->assertTrue( $settings['enable_blocks'] );
	}

	public function test_activation_does_not_overwrite_existing_settings(): void {
		\update_option( 'author_profile_blocks_settings', array( 'preserved' => 'yes' ) );

		\author_profile_blocks()->activate();

		$this->assertSame(
			array( 'preserved' => 'yes' ),
			\get_option( 'author_profile_blocks_settings' )
		);
	}

	public function test_get_setting_falls_back_to_default_value(): void {
		\update_option( 'author_profile_blocks_settings', array() );

		$this->assertSame(
			'fallback',
			\author_profile_blocks()->get_setting( 'undefined_key', 'fallback' )
		);
	}

	public function test_get_setting_returns_stored_value(): void {
		\update_option(
			'author_profile_blocks_settings',
			array( 'cache_duration' => 6 )
		);

		$this->assertSame(
			6,
			\author_profile_blocks()->get_setting( 'cache_duration', 24 )
		);
	}

	public function test_get_setting_filter_runs_per_key(): void {
		\update_option( 'author_profile_blocks_settings', array( 'cache_duration' => 6 ) );

		\add_filter(
			'author_profile_blocks_setting',
			static function ( $value, $key ) {
				return $key === 'cache_duration' ? 12 : $value;
			},
			10,
			2
		);

		$this->assertSame(
			12,
			\author_profile_blocks()->get_setting( 'cache_duration', 24 )
		);
		$this->assertSame(
			'untouched',
			\author_profile_blocks()->get_setting( 'other_key', 'untouched' )
		);
	}

	public function test_deactivation_clears_transient_only(): void {
		\update_option( 'author_profile_blocks_settings', array( 'kept' => true ) );
		\set_transient( 'author_profile_blocks_temp_data', 'gone', 60 );

		\author_profile_blocks()->deactivate();

		$this->assertFalse( \get_transient( 'author_profile_blocks_temp_data' ) );
		$this->assertSame(
			array( 'kept' => true ),
			\get_option( 'author_profile_blocks_settings' ),
			'deactivation must not clear settings'
		);
	}

	public function test_deactivation_fires_action(): void {
		$fired = 0;
		\add_action(
			'author_profile_blocks_deactivated',
			static function () use ( &$fired ) {
				$fired++;
			}
		);

		\author_profile_blocks()->deactivate();
		$this->assertSame( 1, $fired );
	}

	public function test_activation_fires_action(): void {
		$fired = 0;
		\add_action(
			'author_profile_blocks_activated',
			static function () use ( &$fired ) {
				$fired++;
			}
		);

		\author_profile_blocks()->activate();
		$this->assertSame( 1, $fired );
	}
}
