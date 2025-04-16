<?php
/**
 * Base Post Type class
 *
 * @package WPAuthorShowcase
 * @subpackage PostTypes
 */

namespace WPAuthorShowcase\Post_Types;

use WPAuthorShowcase\Core\Base;
use WPAuthorShowcase\Core\Registerable;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract base class for custom post types.
 */
abstract class Base_Post_Type extends Base implements Registerable {
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Initialize the post type.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register' ) );
		$this->additional_init();
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	abstract public function register(): void;

	/**
	 * Additional initialization actions for the post type.
	 * Child classes can override this to add custom actions.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Override in child classes if needed.
	}

	/**
	 * Get post type name.
	 *
	 * @return string The post type name.
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}
}
