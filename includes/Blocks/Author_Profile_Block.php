<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Block class
 *
 * @package AuthorProfileBlocks
 */

namespace APBL\AuthorProfileBlocks\Blocks;

use APBL\AuthorProfileBlocks\Common\Author_Block_Base;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile block.
 */
class Author_Profile_Block extends Author_Block_Base {
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
	 * Localize block script with necessary data
	 *
	 * @return void
	 */
	public function localize_block_script(): void {
		wp_localize_script(
			'author-profile-blocks-author-profile-editor-script',
			'AuthorProfileBlocksData',
			array(
				'adminUrl'    => admin_url(),
				'restNonce'   => wp_create_nonce( 'wp_rest' ),
				'restUrl'     => rest_url(),
				'pluginUrl'   => APBL_PLUGIN_URL,
				'socialIcons' => $this->get_social_icon_data(),
			)
		);
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
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_id = $attributes['authorId'] ?? 0;

		if ( empty( $author_id ) ) {
			return $this->render_error_message( __( 'Please select an author.', 'author-profile-blocks' ) );
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
			return $this->render_error_message( __( 'Author not found.', 'author-profile-blocks' ) );
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

		// Build the HTML.
		$html = '<div ' . $wrapper_attributes . '>';

		// Determine layout based on content order
		$content_order = $attributes['contentOrder'] ?? 'image-content';

		switch ( $content_order ) {
			case 'content-image':
				$html .= $this->render_content_image_layout( $author_data, $attributes );
				break;

			case 'image-top':
				$html .= $this->render_image_top_layout( $author_data, $attributes );
				break;

			case 'content-top':
				$html .= $this->render_content_top_layout( $author_data, $attributes );
				break;

			case 'image-content':
			default:
				$html .= $this->render_image_content_layout( $author_data, $attributes );
				break;
		}

		// Add optional more content if enabled.
		if ( ! empty( $attributes['showMoreContent'] ) && ! empty( $attributes['moreContent'] ) ) {
			$html .= $this->render_more_content( $attributes['moreContent'], $author_data );
		}

		$html .= '</div>';

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Adds styling properties from attributes to author data
	 *
	 * @param array &$author_data Author data reference to modify.
	 * @param array $attributes Block attributes.
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

	/**
	 * Render image-content layout (image left, content right)
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_image_content_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		// Author info.
		$html .= '<div class="apb-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		// Author email - only if email display is enabled in attributes.
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= $this->render_author_email( $author );
		}

		// Registration date - only if registered date display is enabled in attributes.
		if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) {
			$html .= $this->render_registered_date( $author );
		}

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocialLinks'] ) ) {
			$social_links_to_show = $attributes['socialLinksToShow'] ?? array();
			$html                .= $this->render_social_profiles( $author['social'], '', $social_links_to_show );
		}

		$html .= '</div>'; // Close .apb-author-info.
		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render content-image layout (content left, image right)
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_content_image_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author info.
		$html .= '<div class="apb-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		// Author email - only if email display is enabled in attributes.
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= $this->render_author_email( $author );
		}

		// Registration date - only if registered date display is enabled in attributes.
		if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) {
			$html .= $this->render_registered_date( $author );
		}

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocialLinks'] ) ) {
			$social_links_to_show = $attributes['socialLinksToShow'] ?? array();
			$html                .= $this->render_social_profiles( $author['social'], '', $social_links_to_show );
		}

		$html .= '</div>'; // Close .apb-author-info.

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render image-top layout (image above, content below)
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_image_top_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-author-image-centered' );
		}

		// Author info.
		$html .= '<div class="apb-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		// Author email - only if email display is enabled in attributes.
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= $this->render_author_email( $author );
		}

		// Registration date - only if registered date display is enabled in attributes.
		if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) {
			$html .= $this->render_registered_date( $author );
		}

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocialLinks'] ) ) {
			$social_links_to_show = $attributes['socialLinksToShow'] ?? array();
			$html                .= $this->render_social_profiles( $author['social'], 'apb-social-profiles-centered', $social_links_to_show );
		}

		$html .= '</div>'; // Close .apb-author-info.
		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}

	/**
	 * Render content-top layout (content above, image below)
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	private function render_content_top_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-profile-content">';

		// Author info.
		$html .= '<div class="apb-author-info">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		// Author email - only if email display is enabled in attributes.
		if ( ! empty( $author['email'] ) && ( ! isset( $attributes['showEmail'] ) || $attributes['showEmail'] ) ) {
			$html .= $this->render_author_email( $author );
		}

		// Registration date - only if registered date display is enabled in attributes.
		if ( ! empty( $author['registered_date'] ) && ( ! isset( $attributes['showRegisteredDate'] ) || $attributes['showRegisteredDate'] ) ) {
			$html .= $this->render_registered_date( $author );
		}

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocialLinks'] ) ) {
			$social_links_to_show = $attributes['socialLinksToShow'] ?? array();
			$html                .= $this->render_social_profiles( $author['social'], 'apb-social-profiles-centered', $social_links_to_show );
		}

		$html .= '</div>'; // Close .apb-author-info.

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-author-image-centered' );
		}

		$html .= '</div>'; // Close .apb-author-profile-content.

		return $html;
	}
}
