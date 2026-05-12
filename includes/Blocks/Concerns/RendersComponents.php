<?php
declare(strict_types=1);
/**
 * Renders_Components trait
 *
 * Component-renderer helpers shared by all author blocks. Each method loads
 * a `blocks/components/*.php` template via the plugin service and returns the
 * captured HTML. Also exposes the two social-icon lookup tables consumed by
 * the social-profiles template and the editor. Extracted from
 * AuthorBlockBase for separation of concerns.
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks\Concerns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait RendersComponents {

	/**
	 * Render author image section.
	 *
	 * @param array<string, mixed> $author        {
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
	 * @param string               $wrapper_class Optional. Additional CSS class for the image container. Default empty string.
	 * @param array<string, mixed> $attributes    Optional. Block attributes (avatarSize, avatarShape, etc.).
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_image( array $author, string $wrapper_class = '', array $attributes = array() ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'           => $author,
			'attributes'       => $attributes,
			'additional_class' => $wrapper_class,
		);

		// Start output buffering.
		ob_start();

		// Load the author image template.
		author_profile_blocks()->get_template( 'blocks/components/author-image.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render author name section.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_name( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author name template.
		author_profile_blocks()->get_template( 'blocks/components/author-name.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render author position section.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_position( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author position template.
		author_profile_blocks()->get_template( 'blocks/components/author-position.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render author email section.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_email( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author email template.
		author_profile_blocks()->get_template( 'blocks/components/author-email.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render author description section.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_author_description( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the author description template.
		author_profile_blocks()->get_template( 'blocks/components/author-description.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render social profiles section.
	 *
	 * @param array<string, mixed> $profiles      {
	 *       Social profile URLs.
	 *
	 *     @type string $facebook  Facebook profile URL.
	 *     @type string $twitter   Twitter profile URL.
	 *     @type string $linkedin  LinkedIn profile URL.
	 *     @type string $instagram Instagram profile URL.
	 *     @type string $website   Personal website URL.
	 * }
	 * @param string               $wrapper_class Optional. Additional CSS class for the social profiles container. Default empty string.
	 * @param string[]             $show_profiles Optional. List of specific profiles to show. Default empty array.
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_social_profiles( array $profiles, string $wrapper_class = '', array $show_profiles = array() ): string {
		// Prepare template variables.
		$template_vars = array(
			'social_profiles'  => $profiles,
			'additional_class' => $wrapper_class,
			'show_profiles'    => $show_profiles,
		);

		// Start output buffering.
		ob_start();

		// Load the social profiles template.
		author_profile_blocks()->get_template( 'blocks/components/social-profiles.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render registered date section.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_registered_date( array $author ): string {
		// Prepare template variables.
		$template_vars = array(
			'author'     => $author,
			'attributes' => array(), // Not used in this template
		);

		// Start output buffering.
		ob_start();

		// Load the registered date template.
		author_profile_blocks()->get_template( 'blocks/components/registered-date.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Render more content section.
	 *
	 * @param string               $content The content.
	 * @param array<string, mixed> $author  {
	 *     Optional. Author data with style attributes. Default empty array.
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
	 *
	 * @return string Rendered HTML.
	 */
	protected function render_more_content( string $content, array $author = array() ): string {
		if ( '' === $content ) {
			return '';
		}

		// Prepare template variables.
		$template_vars = array(
			'content' => $content,
			'author'  => $author,
		);

		// Start output buffering.
		ob_start();

		// Load the more content template.
		author_profile_blocks()->get_template( 'blocks/components/more-content.php', $template_vars );

		// Return the buffered content.
		$content = ob_get_clean();
		return $content !== false ? $content : '';
	}

	/**
	 * Get social icon data for frontend display.
	 *
	 * @return array<string, string> {
	 *     Social icon data with platform names and dashicon classes.
	 *
	 *     @type string $platform Dashicon class name for the platform.
	 * }
	 */
	protected function get_social_icons(): array {
		return array(
			'facebook'  => 'dashicons-facebook',
			'twitter'   => 'dashicons-twitter',
			'linkedin'  => 'dashicons-linkedin',
			'instagram' => 'dashicons-instagram',
			'website'   => 'dashicons-admin-site',
		);
	}

	/**
	 * Get social icon data for block editor.
	 *
	 * @return array<string, array<string, string>> {
	 *     Social icon data with platform names and icon identifiers for the editor.
	 *
	 *     @type array $platform {
	 *         @type string $name Platform display name.
	 *         @type string $icon Icon identifier.
	 *     }
	 * }
	 */
	protected function get_social_icon_data(): array {
		return array(
			'facebook'  => array(
				'name' => esc_html__( 'Facebook', 'author-profile-blocks' ),
				'icon' => 'facebook',
			),
			'twitter'   => array(
				'name' => esc_html__( 'Twitter', 'author-profile-blocks' ),
				'icon' => 'twitter',
			),
			'linkedin'  => array(
				'name' => esc_html__( 'LinkedIn', 'author-profile-blocks' ),
				'icon' => 'linkedin',
			),
			'instagram' => array(
				'name' => esc_html__( 'Instagram', 'author-profile-blocks' ),
				'icon' => 'instagram',
			),
			'website'   => array(
				'name' => esc_html__( 'Website', 'author-profile-blocks' ),
				'icon' => 'admin-site',
			),
		);
	}
}
