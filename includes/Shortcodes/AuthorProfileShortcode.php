<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Shortcode class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Shortcodes;

use AuthorProfileBlocks\Services\AuthorDataProvider;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides the [apbl_profile] shortcode for rendering a single author profile.
 */
class AuthorProfileShortcode {

	/**
	 * Author data provider instance.
	 *
	 * @var AuthorDataProvider
	 */
	private AuthorDataProvider $provider;

	/**
	 * Constructor.
	 *
	 * @param AuthorDataProvider $provider The author data provider.
	 */
	public function __construct( AuthorDataProvider $provider ) {
		$this->provider = $provider;
	}

	/**
	 * Register the shortcode with WordPress.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'apbl_profile', array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode output.
	 *
	 * @param mixed $atts Shortcode attributes.
	 * @return string Rendered HTML.
	 */
	public function render( $atts ): string {
		$atts = shortcode_atts(
			array(
				'id'           => 0,
				'source'       => 'user',
				'style'        => 'card',
				'show_socials' => 'true',
				'show_bio'     => 'true',
			),
			$atts,
			'apbl_profile'
		);

		$author = $this->provider->get_author( (int) $atts['id'], $atts['source'] );
		if ( ! $author ) {
			return '';
		}

		ob_start();
		$show_socials = 'true' === $atts['show_socials'];
		$show_bio     = 'true' === $atts['show_bio'];
		$style        = sanitize_html_class( $atts['style'] );
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/blocks/components/author-item.php';
		return (string) ob_get_clean();
	}
}
