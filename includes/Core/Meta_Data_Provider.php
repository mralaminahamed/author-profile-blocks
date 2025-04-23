<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Meta Data Provider interface
 *
 * @package AuthorProfileShowcase
 * @subpackage Core
 */

namespace AuthorProfileShowcase\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for classes that provide meta data.
 */
interface Meta_Data_Provider {
	/**
	 * Register meta fields.
	 *
	 * @return void
	 */
	public function register_meta_fields(): void;
}
