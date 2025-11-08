<?php
/**
 * Settings Class
 *
 * @package AuthorProfileBlocks\Admin
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class for managing plugin settings and configuration.
 *
 * Provides centralized access to plugin settings with methods for getting,
 * saving, updating, and sanitizing settings.
 */
class Settings {
	/**
	 * Settings option name.
	 *
	 * @var string
	 */
	public const OPTION_NAME = 'author_profile_blocks_settings';

	/**
	 * Get all plugin settings with defaults merged.
	 *
	 * @return array<string, mixed> Plugin settings merged with defaults.
	 */
	public function get_all(): array {
		$settings = get_option( self::OPTION_NAME, array() );
		$defaults = $this->get_defaults();

		$merged = wp_parse_args( $settings, $defaults );

		/**
		 * Filter the plugin settings.
		 *
		 * @param array $merged The merged settings array.
		 */
		return apply_filters( 'author_profile_blocks_settings', $merged );
	}

	/**
	 * Get a specific setting value.
	 *
	 * @param string $key           Setting key.
	 * @param mixed  $default_value Default value if setting doesn't exist.
	 *
	 * @return mixed The setting value or default.
	 */
	public function get( string $key, $default_value = null ) {
		$settings = $this->get_all();
		$value    = $settings[ $key ] ?? $default_value;

		/**
		 * Filter the specific plugin setting value.
		 *
		 * @param mixed  $value         The setting value.
		 * @param string $key           The setting key.
		 * @param mixed  $default_value The default value.
		 */
		return apply_filters( 'author_profile_blocks_setting', $value, $key, $default_value );
	}

	/**
	 * Update a specific setting.
	 *
	 * @param string $key   Setting key.
	 * @param mixed  $value Setting value.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function update( string $key, $value ): bool {
		$settings         = $this->get_all();
		$settings[ $key ] = $value;

		return $this->save_all( $settings );
	}

	/**
	 * Save all settings at once.
	 *
	 * @param array<string, mixed> $settings Settings array to save.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function save_all( array $settings ): bool {
		$sanitized = $this->sanitize( $settings );

		return update_option( self::OPTION_NAME, $sanitized );
	}

	/**
	 * Delete a specific setting.
	 *
	 * @param string $key Setting key to delete.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( string $key ): bool {
		$settings = $this->get_all();

		if ( isset( $settings[ $key ] ) ) {
			unset( $settings[ $key ] );
			return $this->save_all( $settings );
		}

		return true; // Key didn't exist, so consider it "deleted"
	}

	/**
	 * Check if a setting exists.
	 *
	 * @param string $key Setting key.
	 *
	 * @return bool True if setting exists, false otherwise.
	 */
	public function has( string $key ): bool {
		$settings = $this->get_all();
		return isset( $settings[ $key ] );
	}

	/**
	 * Get default settings.
	 *
	 * @return array<string, mixed> Default settings array.
	 */
	public function get_defaults(): array {
		return array(
			'author_roles'     => array( 'administrator', 'editor', 'author' ),
			'avatar_size'      => 150,
			'social_platforms' => array( 'facebook', 'twitter', 'linkedin', 'instagram' ),
			'show_email'       => 0,
			'cache_duration'   => 24,
			'enable_blocks'    => true,
		);
	}

	/**
	 * Sanitize settings before saving.
	 *
	 * @param array<string, mixed> $input Raw input data.
	 *
	 * @return array<string, mixed> Sanitized settings.
	 */
	public function sanitize( array $input ): array {
		$sanitized = array();

		// Sanitize author roles using WordPress functions
		if ( isset( $input['author_roles'] ) && is_array( $input['author_roles'] ) ) {
			$sanitized['author_roles'] = array_map( 'sanitize_text_field', wp_unslash( $input['author_roles'] ) );
		}

		// Sanitize avatar size
		if ( isset( $input['avatar_size'] ) ) {
			$sanitized['avatar_size'] = absint( wp_unslash( $input['avatar_size'] ) );
			$sanitized['avatar_size'] = max( 32, min( 512, $sanitized['avatar_size'] ) );
		}

		// Sanitize social platforms
		if ( isset( $input['social_platforms'] ) && is_array( $input['social_platforms'] ) ) {
			$sanitized['social_platforms'] = array_map( 'sanitize_text_field', wp_unslash( $input['social_platforms'] ) );
		}

		// Sanitize show email using WordPress boolean function
		$show_email_value        = wp_unslash( $input['show_email'] ?? false );
		$sanitized['show_email'] = rest_sanitize_boolean( $show_email_value ) ? 1 : 0;

		// Sanitize cache duration
		if ( isset( $input['cache_duration'] ) ) {
			$sanitized['cache_duration'] = absint( wp_unslash( $input['cache_duration'] ) );
			$sanitized['cache_duration'] = max( 1, min( 168, $sanitized['cache_duration'] ) );
		}

		// Sanitize enable blocks
		$enable_blocks_value        = wp_unslash( $input['enable_blocks'] ?? true );
		$sanitized['enable_blocks'] = rest_sanitize_boolean( $enable_blocks_value ) ? 1 : 0;

		// Allow other settings to be sanitized by filters
		$sanitized = apply_filters( 'author_profile_blocks_sanitize_settings', $sanitized, $input );

		return $sanitized;
	}

	/**
	 * Reset all settings to defaults.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function reset(): bool {
		return $this->save_all( $this->get_defaults() );
	}

	/**
	 * Initialize default settings if they don't exist.
	 *
	 * @return void
	 */
	public function init_defaults(): void {
		if ( ! get_option( self::OPTION_NAME ) ) {
			$this->save_all( $this->get_defaults() );
		}
	}

	/**
	 * Get settings for REST API.
	 *
	 * @return array<string, mixed> Settings for REST API response.
	 */
	public function get_for_rest(): array {
		return $this->get_all();
	}

	/**
	 * Update multiple settings at once.
	 *
	 * @param array<string, mixed> $new_settings Array of settings to update.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function update_multiple( array $new_settings ): bool {
		$settings = $this->get_all();

		foreach ( $new_settings as $key => $value ) {
			$settings[ $key ] = $value;
		}

		return $this->save_all( $settings );
	}

	/**
	 * Get cache duration in seconds.
	 *
	 * @return int Cache duration in seconds.
	 */
	public function get_cache_duration_seconds(): int {
		$hours = $this->get( 'cache_duration', 24 );
		return $hours * HOUR_IN_SECONDS;
	}

	/**
	 * Check if blocks are enabled.
	 *
	 * @return bool True if blocks are enabled, false otherwise.
	 */
	public function blocks_enabled(): bool {
		return (bool) $this->get( 'enable_blocks', true );
	}

	/**
	 * Get enabled social platforms.
	 *
	 * @return array<string> Array of enabled social platform keys.
	 */
	public function get_enabled_social_platforms(): array {
		$platforms = $this->get( 'social_platforms', array() );
		return is_array( $platforms ) ? $platforms : array();
	}

	/**
	 * Check if email display is enabled.
	 *
	 * @return bool True if email display is enabled, false otherwise.
	 */
	public function show_email_enabled(): bool {
		return (bool) $this->get( 'show_email', false );
	}

	/**
	 * Get enabled author roles.
	 *
	 * @return array<string> Array of enabled author role keys.
	 */
	public function get_enabled_author_roles(): array {
		$roles = $this->get( 'author_roles', array() );
		return is_array( $roles ) ? $roles : array();
	}

	/**
	 * Get avatar size setting.
	 *
	 * @return int Avatar size in pixels.
	 */
	public function get_avatar_size(): int {
		return (int) $this->get( 'avatar_size', 150 );
	}
}
