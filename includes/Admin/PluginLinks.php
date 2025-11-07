<?php

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Links Settings Class
 *
 * @package AuthorProfileBlocks
 */

/**
 * Plugin Links Settings Class
 */
class PluginLinks {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_' . plugin_basename( APBL_PLUGIN_FILE ), array( $this, 'add_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_row_meta' ), 10, 2 );
	}

	/**
	 * Add action links to plugin listing
	 *
	 * Adds settings link to the plugin action links.
	 *
	 * @param array<int, string> $links Existing action links.
	 * @return array<int, string> Modified action links.
	 */
	public function add_action_links( array $links ): array {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=author-profile-blocks' ),
			__( 'Settings', 'author-profile-blocks' )
		);

		array_unshift( $links, $settings_link );

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
	public function add_row_meta( array $links, string $file ): array {
		if ( plugin_basename( APBL_PLUGIN_FILE ) !== $file ) {
			return $links;
		}

		$docs_link = sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'https://github.com/mralaminahamed/author-profile-blocks',
			__( 'Documentation', 'author-profile-blocks' )
		);

		$github_link = sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'https://github.com/mralaminahamed/author-profile-blocks',
			__( 'GitHub', 'author-profile-blocks' )
		);

		$links[] = $docs_link;
		$links[] = $github_link;

		return $links;
	}
}
