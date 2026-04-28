<?php
declare(strict_types=1);
/**
 * REST Settings Controller
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
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

	const NAMESPACE = 'author-profile-blocks/v1';
	const ROUTE     = '/settings';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
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

	public function check_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	public function get_settings(): WP_REST_Response {
		$settings               = Admin::get_settings();
		$settings['show_email'] = (bool) $settings['show_email'];
		return new WP_REST_Response( $settings, 200 );
	}

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
			$sanitized['avatar_size'] = wp_clamp( absint( $params['avatar_size'] ), 32, 512 );
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
			$sanitized['cache_duration'] = wp_clamp( absint( $params['cache_duration'] ), 1, 168 );
		}

		$updated               = array_merge( Admin::get_settings(), $sanitized );
		$updated['show_email'] = (bool) $updated['show_email'];
		update_option( 'author_profile_blocks_settings', array_merge( Admin::get_settings(), $sanitized ) );

		return new WP_REST_Response( $updated, 200 );
	}
}
