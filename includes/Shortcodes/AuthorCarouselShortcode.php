<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Carousel Shortcode class
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
 * Provides the [apbl_carousel] shortcode for rendering authors in a carousel.
 */
class AuthorCarouselShortcode {

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
		add_shortcode( 'apbl_carousel', array( $this, 'render' ) );
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
				'ids'      => '',
				'source'   => 'user',
				'role'     => '',
				'style'    => 'modern',
				'autoplay' => 'false',
				'number'   => 10,
			),
			$atts,
			'apbl_carousel'
		);

		$include = ! empty( $atts['ids'] ) ? array_map( 'intval', explode( ',', $atts['ids'] ) ) : array();
		$authors = $this->provider->get_authors(
			array(
				'source'  => $atts['source'],
				'include' => $include,
				'role'    => $atts['role'],
				'number'  => (int) $atts['number'],
			)
		);

		if ( empty( $authors ) ) {
			return '';
		}

		ob_start();
		$style    = sanitize_html_class( $atts['style'] );
		$autoplay = 'true' === $atts['autoplay'];
		foreach ( $authors as $author ) {
			include APBL_PLUGIN_PATH . 'templates/blocks/author-carousel/slide.php';
		}
		return (string) ob_get_clean();
	}
}
