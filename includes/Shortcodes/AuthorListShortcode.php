<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author List Shortcode class
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
 * Provides the [apbl_list] shortcode for rendering authors in a vertical list.
 */
class AuthorListShortcode {

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
		add_shortcode( 'apbl_list', array( $this, 'render' ) );
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
				'ids'    => '',
				'source' => 'user',
				'role'   => '',
				'style'  => 'detailed',
				'number' => 10,
			),
			$atts,
			'apbl_list'
		);

		$block = author_profile_blocks()->get_block( 'author-list' );
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

		$style         = sanitize_html_class( $atts['style'] );
		$display_style = in_array( $style, array( 'compact', 'detailed', 'minimal' ), true ) ? $style : 'detailed';
		$attributes    = array(
			'displayStyle'    => $display_style,
			'showSocial'      => true,
			'showSocialLinks' => true,
		);

		$wrapper_attributes = sprintf(
			'class="%s"',
			esc_attr( 'wp-block-author-profile-blocks-author-list apbl-shortcode is-style-' . $style )
		);

		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-list/list.php',
			array(
				'authors'            => $authors,
				'attributes'         => $attributes,
				'block_instance'     => $block,
				'wrapper_attributes' => $wrapper_attributes,
			)
		);
		return (string) ob_get_clean();
	}
}
