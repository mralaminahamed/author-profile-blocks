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

		$block = author_profile_blocks()->get_block( 'author-grid' );
		if ( null === $block ) {
			return '';
		}

		$author = $this->provider->get_author( (int) $atts['id'], $atts['source'] );
		if ( ! $author ) {
			return '';
		}

		if ( 'true' !== $atts['show_bio'] ) {
			unset( $author['description'], $author['bio'] );
		}

		$style      = sanitize_html_class( $atts['style'] );
		$attributes = array(
			'layout'     => $style,
			'columns'    => 1,
			'showSocial' => 'true' === $atts['show_socials'],
		);

		$wrapper_attributes = sprintf(
			'class="%s"',
			esc_attr( 'wp-block-author-profile-blocks-author-profile apbl-shortcode is-style-' . $style )
		);

		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-grid/grid.php',
			array(
				'authors'            => array( $author ),
				'attributes'         => $attributes,
				'block_instance'     => $block,
				'wrapper_attributes' => $wrapper_attributes,
			)
		);
		return (string) ob_get_clean();
	}
}
