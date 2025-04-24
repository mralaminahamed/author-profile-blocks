<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Layout Renderer Trait
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Common;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for rendering different layout patterns consistently across blocks.
 */
trait Layout_Renderer {
	/**
	 * Render compact layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_compact_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-compact">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		// Author info container.
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

		// Author description in compact mode might be truncated.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		$html .= '</div>'; // Close .apb-author-info.

		// Social icons if enabled.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apb-author-social">';
			$html .= $this->render_social_profiles( $author['social'], 'apb-compact-social' );
			$html .= '</div>';
		}

		$html .= '</div>'; // Close .apb-author-compact.

		return $html;
	}

	/**
	 * Render detailed layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_detailed_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-detailed">';

		// Left column with image.
		$html .= '<div class="apb-author-left">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author );
		}

		$html .= '</div>'; // Close .apb-author-left.

		// Right column with author details.
		$html .= '<div class="apb-author-right">';

		// Author info header.
		$html .= '<div class="apb-author-header">';

		// Author name.
		if ( ! empty( $author['title'] ) ) {
			$html .= $this->render_author_name( $author );
		}

		// Author position if available and display is enabled.
		if ( ! empty( $author['position'] ) && ( ! isset( $attributes['showPosition'] ) || $attributes['showPosition'] ) ) {
			$html .= $this->render_author_position( $author );
		}

		$html .= '</div>'; // Close .apb-author-header.

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

		// Social profiles in footer if enabled.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apb-author-footer">';
			$html .= $this->render_social_profiles( $author['social'], 'apb-detailed-social' );
			$html .= '</div>';
		}

		$html .= '</div>'; // Close .apb-author-right.
		$html .= '</div>'; // Close .apb-author-detailed.

		return $html;
	}

	/**
	 * Render card layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_card_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-card">';

		// Card header.
		$html .= '<div class="apb-card-header">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-card-image' );
		}

		$html .= '</div>'; // Close .apb-card-header.

		// Card body.
		$html .= '<div class="apb-card-body">';

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

		$html .= '</div>'; // Close .apb-card-body.

		// Card footer with social profiles.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= '<div class="apb-card-footer">';
			$html .= $this->render_social_profiles( $author['social'] );
			$html .= '</div>'; // Close .apb-card-footer.
		}

		$html .= '</div>'; // Close .apb-author-card.

		return $html;
	}

	/**
	 * Render centered layout for author profiles.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_centered_layout( array $author, array $attributes ): string {
		$html = '<div class="apb-author-centered">';

		// Author image - only if image display is enabled in attributes.
		if ( ! empty( $author['image'] ) && ( ! isset( $attributes['showImage'] ) || $attributes['showImage'] ) ) {
			$html .= $this->render_author_image( $author, 'apb-centered-image' );
		}

		// Author info container.
		$html .= '<div class="apb-author-centered-info">';

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

		// Social profiles - only if display is enabled in attributes.
		if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ( ! isset( $attributes['showSocial'] ) || $attributes['showSocial'] ) ) {
			$html .= $this->render_social_profiles( $author['social'], 'apb-centered-social' );
		}

		$html .= '</div>'; // Close .apb-author-centered-info.

		// Author description - only if description display is enabled in attributes.
		if ( ! empty( $author['description'] ) && ( ! isset( $attributes['showDescription'] ) || $attributes['showDescription'] ) ) {
			$html .= $this->render_author_description( $author );
		}

		$html .= '</div>'; // Close .apb-author-centered.

		return $html;
	}
}
