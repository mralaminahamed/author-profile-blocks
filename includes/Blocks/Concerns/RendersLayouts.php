<?php
declare(strict_types=1);
/**
 * Renders_Layouts trait
 *
 * Layout-renderer helpers shared by all author blocks. Each method assembles
 * the per-layout template variables (delegating to component renderers from
 * the RendersComponents trait), loads the matching `blocks/layouts/*.php`
 * template via the plugin service, and returns the captured HTML. Extracted
 * from AuthorBlockBase for separation of concerns.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait RendersLayouts {

	/**
	 * Render compact layout for author profiles.
	 *
	 * @param array<string, mixed> $author     {
	 *     Author data.
	 *
	 *     @type int    $id             User ID.
	 *     @type string $name           Display name.
	 *     @type string $email          Email address.
	 *     @type string $image          Avatar URL.
	 *     @type string $position       Job position/title.
	 *     @type string $description    Bio description.
	 *     @type array  $social         Social media profiles.
	 *     @type string $registered_date Registration date.
	 * }
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_compact_layout( array $author, array $attributes ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, '', $attributes ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'social_links'       => $this->render_social_profiles( is_array( $author['social'] ?? null ) ? $author['social'] : array(), 'apbl-compact-social' ),
		);

		// Start output buffering.
		ob_start();

		// Load the compact layout template.
		author_profile_blocks()->get_template( 'blocks/layouts/compact.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render detailed layout for author profiles.
	 *
	 * @param array<string, mixed> $author     {
	 *     Author data.
	 *
	 *     @type int    $id             User ID.
	 *     @type string $name           Display name.
	 *     @type string $email          Email address.
	 *     @type string $image          Avatar URL.
	 *     @type string $position       Job position/title.
	 *     @type string $description    Bio description.
	 *     @type array  $social         Social media profiles.
	 *     @type string $registered_date Registration date.
	 * }
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_detailed_layout( array $author, array $attributes ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, '', $attributes ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'registered_date'    => $this->render_registered_date( $author ),
			'social_links'       => $this->render_social_profiles( is_array( $author['social'] ?? null ) ? $author['social'] : array(), 'apbl-detailed-social' ),
		);

		// Start output buffering.
		ob_start();

		// Load the detailed layout template.
		author_profile_blocks()->get_template( 'blocks/layouts/detailed.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render card layout for author profiles.
	 *
	 * @param array $author     {
	 *     Author data.
	 *
	 *     @type int    $id             User ID.
	 *     @type string $name           Display name.
	 *     @type string $email          Email address.
	 *     @type string $image          Avatar URL.
	 *     @type string $position       Job position/title.
	 *     @type string $description    Bio description.
	 *     @type array  $social         Social media profiles.
	 *     @type string $registered_date Registration date.
	 * }
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_card_layout( array $author, array $attributes ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, 'apbl-card-image', $attributes ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'registered_date'    => $this->render_registered_date( $author ),
			'social_links'       => $this->render_social_profiles( is_array( $author['social'] ?? null ) ? $author['social'] : array() ),
		);

		// Start output buffering.
		ob_start();

		// Load the card layout template.
		author_profile_blocks()->get_template( 'blocks/layouts/card.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render card layout for author profiles.
	 *
	 * @param array<string, mixed> $author {
	 *     Author data.
	 *
	 *     @type int    $id             User ID.
	 *     @type string $name           Display name.
	 *     @type string $email          Email address.
	 *     @type string $image          Avatar URL.
	 *     @type string $position       Job position/title.
	 *     @type string $description    Bio description.
	 *     @type array  $social         Social media profiles.
	 *     @type string $registered_date Registration date.
	 * }
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_centered_layout( array $author, array $attributes ): string {
		$social_profiles = is_array( $author['social'] ?? null ) ? $author['social'] : array();
		$template_vars = array(
			'author'             => $author,
			'attributes'         => $attributes,
			'author_image'       => $this->render_author_image( $author, '', $attributes ),
			'author_name'        => $this->render_author_name( $author ),
			'author_position'    => $this->render_author_position( $author ),
			'author_email'       => $this->render_author_email( $author ),
			'author_description' => $this->render_author_description( $author ),
			'social_links'       => $this->render_social_profiles( $social_profiles ),
		);

		ob_start();

		// Load the centered layout template.
		author_profile_blocks()->get_template( 'blocks/layouts/centered.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render minimal layout for author profiles.
	 *
	 * @param array<string, mixed> $author {
	 *     Author data.
	 *
	 *     @type int    $id             User ID.
	 *     @type string $name           Display name.
	 *     @type string $email          Email address.
	 *     @type string $description    Bio description.
	 *     @type array  $social         Social media profiles.
	 *     @type string $registered_date Registration date.
	 * }
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_minimal_layout( array $author, array $attributes ): string {
		$template_vars = array(
			'author'     => $author,
			'attributes' => $attributes,
		);

		ob_start();

		// Load the minimal layout template.
		author_profile_blocks()->get_template( 'blocks/layouts/minimal.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}
}
