<?php
/**
 * Plugin Links Settings Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Links Settings Class for managing plugin action links and row meta.
 *
 * Adds settings links to the plugin listing page and provides
 * documentation and GitHub links in the plugin row meta.
 */
class Plugin_Links {

	/**
	 * Constructor.
	 *
	 * Sets up filters for plugin action links and row meta.
	 */
	public static function init() {
		add_filter( 'plugin_action_links_' . plugin_basename( APBL_PLUGIN_FILE ), array( self::class, 'add_action_links' ) );
		add_filter( 'plugin_row_meta', array( self::class, 'add_row_meta' ), 10, 2 );
	}

	/**
	 * Add action links to plugin listing
	 *
	 * Adds settings link to the plugin action links.
	 *
	 * @param array<int, string> $links Existing action links.
	 * @return array<int, string> Modified action links.
	 */
	public static function add_action_links( array $links ): array {
		$settings_url = add_query_arg(
			array( 'page' => 'author-profile-blocks' ),
			admin_url( 'options-general.php' )
		);

		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $settings_url ),
			esc_html__( 'Settings', 'author-profile-blocks' )
		);

		array_unshift( $links, wp_kses_post( $settings_link ) );

		return $links;
	}

	/**
	 * Add row meta to plugin listing
	 *
	 * Adds documentation and GitHub links to the plugin row meta.
	 *
	 * @param array<int, string> $links Existing row meta.
	 * @param string             $file  Plugin file.
	 * @return array<int, string> Modified row meta.
	 */
	public static function add_row_meta( array $links, string $file ): array {
		if ( plugin_basename( APBL_PLUGIN_FILE ) !== $file ) {
			return $links;
		}

		$repository_url = 'https://github.com/mralaminahamed/author-profile-blocks';

		$docs_link = sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			esc_url( $repository_url ),
			esc_html__( 'Documentation', 'author-profile-blocks' )
		);

		$github_link = sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			esc_url( $repository_url ),
			esc_html__( 'GitHub', 'author-profile-blocks' )
		);

		$links[] = wp_kses_post( $docs_link );
		$links[] = wp_kses_post( $github_link );

		return $links;
	}
}
