<?php
/**
 * Template Loader class
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 */

namespace WPAuthorShowcase\Templates;

use WPAuthorShowcase\Core\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles template loading.
 */
class Template_Loader extends Base {
	/**
	 * Initialize the template loader.
	 *
	 * @return void
	 */
	public function init(): void {
		// Nothing to initialize by default.
	}

	/**
	 * Load a template part.
	 *
	 * @param string $template Template name.
	 * @param array  $args     Arguments to pass to the template.
	 * @param bool   $echo     Whether to echo the template or return it.
	 * @return string|void Template content if $echo is false, void otherwise.
	 */
	public function get_template_part( string $template, array $args = array(), bool $echo = true ) {
		$template_path = WPAS_PLUGIN_DIR . 'templates/' . $template . '.php';

		if ( ! file_exists( $template_path ) ) {
			return '';
		}

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		ob_start();
		include $template_path;
		$output = ob_get_clean();

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}
