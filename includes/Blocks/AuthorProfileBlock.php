<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Block class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\AuthorBlockBase;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class AuthorProfileBlock extends AuthorBlockBase {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-profile';
	}

	/**
	 * Block-specific initialization.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		add_action( 'enqueue_block_editor_assets', array( $this, 'localize_block_script' ) );
	}

	/**
	 * Get render callback for the block.
	 *
	 * @return callable|null Block render callback.
	 */
	protected function get_render_callback(): ?callable {
		return array( $this, 'render_callback' );
	}

	/**
	 * Render callback for the block.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 * @param string               $content    Block content.
	 * @param WP_Block             $block      Block instance.
	 *
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_id = (int) ( $attributes['authorId'] ?? 0 );

		if ( empty( $author_id ) ) {
			return $this->render_error_message( $this->get_no_author_selected_message() );
		}

		// Check cache first.
		$cache_key      = $this->generate_cache_key( $author_id, $attributes );
		$cached_content = $this->get_cached_render( $cache_key );
		if ( $cached_content ) {
			return $cached_content;
		}

		// Get author data.
		$author_data = $this->get_author_data( $author_id );

		if ( ! $author_data ) {
			return $this->render_error_message( $this->get_author_not_found_message() );
		}

		// Add styling properties to author data
		$this->add_style_properties_to_author_data( $author_data, $attributes );

		// Generate styles for the block.
		$styles          = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $styles ) ) {
			$style_attribute = $this->get_styles_string( $styles );
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes, 'profile' ),
				'style' => $style_attribute,
			)
		);

		// Determine layout based on content order
		$content_order = $attributes['contentOrder'] ?? 'image-content';

		// Pre-render content for template
		$author_image       = $this->render_author_image( $author_data, '', $attributes );
		$author_name        = $this->render_author_name( $author_data );
		$author_position    = $this->render_author_position( $author_data );
		$author_email       = $this->render_author_email( $author_data );
		$registered_date    = $this->render_registered_date( $author_data );
		$author_description = $this->render_author_description( $author_data );
		$social_links       = ! empty( $author_data['social'] ) && is_array( $author_data['social'] ) && ! empty( $attributes['showSocialLinks'] )
			? $this->render_social_profiles( $author_data['social'], '', $attributes['socialLinksToShow'] ?? array() )
			: '';
		$more_content       = ( ! empty( $attributes['showMoreContent'] ) && ! empty( $attributes['moreContent'] ) )
			? $this->render_more_content( $attributes['moreContent'], $author_data )
			: '';

		// Build the HTML using template.
		ob_start();
		author_profile_blocks()->get_template(
			'blocks/author-profile/wrapper.php',
			array(
				'author'             => $author_data,
				'attributes'         => $attributes,
				'wrapper_attributes' => $wrapper_attributes,
				'content_order'      => $content_order,
				'author_image'       => $author_image,
				'author_name'        => $author_name,
				'author_position'    => $author_position,
				'author_email'       => $author_email,
				'registered_date'    => $registered_date,
				'author_description' => $author_description,
				'social_links'       => $social_links,
				'more_content'       => $more_content,
				'block_instance'     => $this,
			)
		);
		$html = ob_get_clean();

		// Ensure we have valid HTML content
		$content = $html !== false ? $html : '';

		// Cache the result.
		$this->set_cached_render( $cache_key, $content );

		return $content;
	}

	/**
	 * Adds styling properties from attributes to author data
	 *
	 * @param array<string, mixed> &$author_data Author data reference to modify.
	 * @param array<string, mixed> $attributes  Block attributes.
	 *
	 * @return void
	 */
	private function add_style_properties_to_author_data( array &$author_data, array $attributes ): void {
		// Avatar styles
		if ( isset( $attributes['avatarSize'] ) ) {
			$author_data['avatarSize'] = $attributes['avatarSize'];
		}

		if ( isset( $attributes['avatarShape'] ) ) {
			$author_data['avatarShape'] = $attributes['avatarShape'];
		}

		if ( isset( $attributes['avatarBorderWidth'] ) ) {
			$author_data['avatarBorderWidth'] = $attributes['avatarBorderWidth'];
		}

		if ( isset( $attributes['avatarBorderColor'] ) ) {
			$author_data['avatarBorderColor'] = $attributes['avatarBorderColor'];
		}

		if ( isset( $attributes['avatarBorderRadius'] ) ) {
			$author_data['avatarBorderRadius'] = $attributes['avatarBorderRadius'];
		}

		if ( isset( $attributes['avatarAlignment'] ) ) {
			$author_data['avatarAlignment'] = $attributes['avatarAlignment'];
		}

		if ( isset( $attributes['avatarMargin'] ) ) {
			$author_data['avatarMargin'] = $attributes['avatarMargin'];
		}

		// Typography styles
		if ( isset( $attributes['nameColor'] ) ) {
			$author_data['nameColor'] = $attributes['nameColor'];
		}

		if ( isset( $attributes['nameSize'] ) ) {
			$author_data['nameSize'] = $attributes['nameSize'];
		}

		if ( isset( $attributes['nameWeight'] ) ) {
			$author_data['nameWeight'] = $attributes['nameWeight'];
		}

		if ( isset( $attributes['nameTransform'] ) ) {
			$author_data['nameTransform'] = $attributes['nameTransform'];
		}

		if ( isset( $attributes['nameAlignment'] ) ) {
			$author_data['nameAlignment'] = $attributes['nameAlignment'];
		}

		if ( isset( $attributes['nameMargin'] ) ) {
			$author_data['nameMargin'] = $attributes['nameMargin'];
		}

		if ( isset( $attributes['descriptionColor'] ) ) {
			$author_data['descriptionColor'] = $attributes['descriptionColor'];
		}

		if ( isset( $attributes['descriptionSize'] ) ) {
			$author_data['descriptionSize'] = $attributes['descriptionSize'];
		}

		if ( isset( $attributes['descriptionLineHeight'] ) ) {
			$author_data['descriptionLineHeight'] = $attributes['descriptionLineHeight'];
		}

		if ( isset( $attributes['descriptionStyle'] ) ) {
			$author_data['descriptionStyle'] = $attributes['descriptionStyle'];
		}

		if ( isset( $attributes['descriptionAlignment'] ) ) {
			$author_data['descriptionAlignment'] = $attributes['descriptionAlignment'];
		}

		if ( isset( $attributes['descriptionMargin'] ) ) {
			$author_data['descriptionMargin'] = $attributes['descriptionMargin'];
		}

		if ( isset( $attributes['metaColor'] ) ) {
			$author_data['metaColor'] = $attributes['metaColor'];
		}

		if ( isset( $attributes['metaSize'] ) ) {
			$author_data['metaSize'] = $attributes['metaSize'];
		}

		if ( isset( $attributes['metaStyle'] ) ) {
			$author_data['metaStyle'] = $attributes['metaStyle'];
		}

		if ( isset( $attributes['metaBold'] ) ) {
			$author_data['metaBold'] = $attributes['metaBold'];
		}

		if ( isset( $attributes['metaAlignment'] ) ) {
			$author_data['metaAlignment'] = $attributes['metaAlignment'];
		}

		if ( isset( $attributes['metaMargin'] ) ) {
			$author_data['metaMargin'] = $attributes['metaMargin'];
		}

		if ( isset( $attributes['emailLinkColor'] ) ) {
			$author_data['emailLinkColor'] = $attributes['emailLinkColor'];
		}

		if ( isset( $attributes['emailHoverColor'] ) ) {
			$author_data['emailHoverColor'] = $attributes['emailHoverColor'];
		}

		// Social media styles
		if ( isset( $attributes['socialIconSize'] ) ) {
			$author_data['socialIconSize'] = $attributes['socialIconSize'];
		}

		if ( isset( $attributes['socialIconColor'] ) ) {
			$author_data['socialIconColor'] = $attributes['socialIconColor'];
		}

		if ( isset( $attributes['socialIconHoverColor'] ) ) {
			$author_data['socialIconHoverColor'] = $attributes['socialIconHoverColor'];
		}

		if ( isset( $attributes['socialIconBackground'] ) ) {
			$author_data['socialIconBackground'] = $attributes['socialIconBackground'];
		}

		if ( isset( $attributes['socialIconBackgroundHover'] ) ) {
			$author_data['socialIconBackgroundHover'] = $attributes['socialIconBackgroundHover'];
		}

		if ( isset( $attributes['socialIconSpacing'] ) ) {
			$author_data['socialIconSpacing'] = $attributes['socialIconSpacing'];
		}

		if ( isset( $attributes['socialIconAlignment'] ) ) {
			$author_data['socialIconAlignment'] = $attributes['socialIconAlignment'];
		}

		// More content styles
		if ( isset( $attributes['moreContentBorderColor'] ) ) {
			$author_data['moreContentBorderColor'] = $attributes['moreContentBorderColor'];
		}

		if ( isset( $attributes['moreContentPadding'] ) ) {
			$author_data['moreContentPadding'] = $attributes['moreContentPadding'];
		}
	}
}
