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

		$block = author_profile_blocks()->get_block( 'author-carousel' );
		if ( null === $block ) {
			return '';
		}

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

		// The carousel view script (Slick init) is registered by the block on wp_enqueue_scripts.
		if ( wp_script_is( 'author-carousel-view', 'registered' ) ) {
			wp_enqueue_script( 'author-carousel-view' );
		}

		$style      = sanitize_html_class( $atts['style'] );
		$autoplay   = 'true' === $atts['autoplay'];
		$attributes = array(
			'layout'     => $style,
			'autoplay'   => $autoplay,
			'showSocial' => true,
		);

		$carousel_settings = array(
			'slidesToShow'   => 3,
			'slidesToScroll' => 1,
			'autoplay'       => $autoplay,
			'autoplaySpeed'  => 3000,
			'dots'           => true,
			'arrows'         => true,
			'infinite'       => true,
		);

		$wrapper_attributes = sprintf(
			'class="%s"',
			esc_attr( 'wp-block-author-profile-blocks-author-carousel apbl-shortcode is-style-' . $style )
		);

		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-carousel/frontend.php',
			array(
				'authors'            => $authors,
				'attributes'         => $attributes,
				'block_instance'     => $block,
				'wrapper_attributes' => $wrapper_attributes,
				'carousel_settings'  => $carousel_settings,
			)
		);
		return (string) ob_get_clean();
	}
}
