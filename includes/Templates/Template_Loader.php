<?php
/**
 * Template Loader class
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 */

namespace WPAuthorShowcase\Templates;

use function file_exists;
use function ob_get_clean;
use function ob_start;
use function esc_html;

/**
 * Class that handles loading templates.
 */
class Template_Loader {
	/**
	 * Load a template file.
	 *
	 * @param string $template_name The template name.
	 * @param array  $args          The template arguments.
	 * @param bool   $echo          Whether to echo the template or return it.
	 * @return string|void The template content if $echo is false.
	 */
	public static function load( string $template_name, array $args = array(), bool $echo = true ) {
		$template_path = WPAS_PLUGIN_DIR . 'templates/' . $template_name . '.php';

		// Check if template exists.
		if ( ! file_exists( $template_path ) ) {
			// Template not found, return/echo an error message.
			$message = sprintf( 'Template file not found: %s', $template_path );
			if ( $echo ) {
				echo esc_html( $message );
				return;
			}
			return $message;
		}

		// Start output buffering if we're returning the template.
		if ( ! $echo ) {
			ob_start();
		}

		// Set variables for use in template.
		foreach ( $args as $key => $value ) {
			$$key = $value;
		}

		// Include the template file.
		include $template_path;

		// Return the buffered content if we're not echoing.
		if ( ! $echo ) {
			return ob_get_clean();
		}

		return '';
	}
}
