<?php
declare(strict_types=1);
/**
 * REST Settings Controller
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\REST;

use AuthorProfileBlocks\Admin\Admin;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles GET and POST for plugin settings via REST API.
 */
class Settings {

	const REST_NAMESPACE = 'author-profile-blocks/v1';
	const ROUTE          = '/settings';

	/**
	 * Wire up REST route registration.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register GET and POST routes for /v1/settings.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			self::REST_NAMESPACE,
			self::ROUTE,
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Allow only users with manage_options capability.
	 *
	 * @return bool
	 */
	public function check_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Return current plugin settings.
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings(): WP_REST_Response {
		$settings               = Admin::get_settings();
		$settings['show_email'] = (bool) $settings['show_email'];
		return new WP_REST_Response( $settings, 200 );
	}

	/**
	 * Sanitize and persist plugin settings.
	 *
	 * @param WP_REST_Request $request REST request with JSON body.
	 *
	 * @return WP_REST_Response
	 */
	public function update_settings( WP_REST_Request $request ): WP_REST_Response {
		$params    = $request->get_json_params() ?? array();
		$sanitized = array();

		$valid_roles = array_keys( wp_roles()->roles );
		if ( isset( $params['author_roles'] ) && is_array( $params['author_roles'] ) ) {
			$sanitized['author_roles'] = array_values(
				array_filter(
					array_map( 'sanitize_text_field', $params['author_roles'] ),
					fn( $r ) => in_array( $r, $valid_roles, true )
				)
			);
		}

		if ( isset( $params['avatar_size'] ) ) {
			$sanitized['avatar_size'] = max( 32, min( 512, absint( $params['avatar_size'] ) ) );
		}

		$valid_platforms = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'website' );
		if ( isset( $params['social_platforms'] ) && is_array( $params['social_platforms'] ) ) {
			$sanitized['social_platforms'] = array_values(
				array_filter(
					array_map( 'sanitize_text_field', $params['social_platforms'] ),
					fn( $p ) => in_array( $p, $valid_platforms, true )
				)
			);
		}

		if ( isset( $params['show_email'] ) ) {
			$sanitized['show_email'] = rest_sanitize_boolean( $params['show_email'] ) ? 1 : 0;
		}

		if ( isset( $params['cache_duration'] ) ) {
			$sanitized['cache_duration'] = max( 1, min( 168, absint( $params['cache_duration'] ) ) );
		}

		$base   = Admin::get_settings();
		$merged = array_merge( $base, $sanitized );
		update_option( 'author_profile_blocks_settings', $merged );

		$merged['show_email'] = (bool) $merged['show_email'];
		return new WP_REST_Response( $merged, 200 );
	}
}
