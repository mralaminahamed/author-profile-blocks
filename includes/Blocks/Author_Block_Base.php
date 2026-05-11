<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Block Base
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\Concerns\Builds_Block_Classes;
use AuthorProfileBlocks\Blocks\Concerns\Has_Render_Cache;
use AuthorProfileBlocks\Blocks\Concerns\Provides_Messages;
use AuthorProfileBlocks\Blocks\Concerns\Resolves_Author_Data;
use AuthorProfileBlocks\Core\Registerable;
use Author_Profile_Blocks;

/**
 * Abstract base class for author-related blocks.
 */
abstract class Author_Block_Base implements Registerable {
	use Provides_Messages;
	use Resolves_Author_Data;
	use Has_Render_Cache;
	use Builds_Block_Classes;

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected string $block_name;

	/**
	 * Cache for rendered content.
	 *
	 * @var array<string, string>
	 */
	protected array $render_cache = array();

	/**
	 * Block constructor.
	 *
	 * Initializes the block with its name.
	 */
	public function __construct() {
		$this->block_name = $this->get_block_name();
	}

	/**
	 * Initialize the block.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register();
		$this->additional_init();
	}

	/**
	 * Get the block name.
	 *
	 * This is used both internally and externally to identify the block.
	 *
	 * @return string Block name.
	 */
	abstract public function get_block_name(): string;

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 *
	 * @return void
	 */
	public function register(): void {
		$block_path = APBL_PLUGIN_PATH . 'build/blocks/' . $this->block_name;

		// Check if block.json exists
		if ( ! file_exists( $block_path . '/block.json' ) ) {
			return;
		}

		$args = array();

		// If there's a render callback, add it to the args.
		$callback = $this->get_render_callback();
		if ( $callback ) {
			$args['render_callback'] = $callback;
		}

		// Apply custom block registration filters.
		$args = apply_filters( 'author_profile_blocks_block_args', $args, $this->block_name );

		// Register block using block.json metadata.
		$result = register_block_type( $block_path, $args );

		if ( ! $result ) {
			return;
		}

		// Trigger action after registration.
		do_action( 'author_profile_blocks_block_registered', $this->block_name, $this );
	}

	/**
	 * Additional initialization actions for the block.
	 *
	 * @return void
	 */
	protected function additional_init(): void {
		// Register filter for rendered output.
		add_filter( 'render_block_author-profile-blocks/' . $this->block_name, array( $this, 'filter_rendered_output' ), 10, 2 );

		// Add any block-specific initialization.
		$this->block_specific_init();
	}

	/**
	 * Get render callback for the block.
	 *
	 * Child classes should override this method if they need a render callback.
	 * Returns null by default for dynamic blocks that use block.json rendering.
	 *
	 * @return callable|null Block render callback or null.
	 */
	protected function get_render_callback(): ?callable {
		return null;
	}

	/**
	 * Block-specific initialization.
	 *
	 * Override in child classes to add block-specific initialization logic.
	 *
	 * @return void
	 */
	protected function block_specific_init(): void {
		// Override in child classes if needed.
	}

	/**
	 * Filter the rendered output of the block.
	 *
	 * This is a final processing step after the block renders. Applies both
	 * block-specific and general filters to allow customization of output.
	 *
	 * @param string               $block_content The rendered block content.
	 * @param array<string, mixed> $block         {
	 *     The block data including attributes and inner content.
	 *
	 *     @type array<string, mixed> $attributes Block attributes.
	 *     @type string              $innerHTML  Block inner HTML content.
	 *     @type array<string, mixed> $innerBlocks Array of inner blocks.
	 * }
	 *
	 * @return string The filtered content.
	 */
	public function filter_rendered_output( string $block_content, array $block ): string {
		// Apply a block-specific filter first.
		$filtered_content = apply_filters(
			'author_profile_blocks_rendered_' . str_replace( '-', '_', $this->block_name ),
			$block_content,
			$block
		);

		// Then apply a general filter for all blocks.
		return apply_filters( 'author_profile_blocks_rendered_block', $filtered_content, $block, $this->block_name );
	}

	/**
	 * Localize block script with common data.
	 *
	 * Adds JavaScript variables to the block editor script for use in the editor.
	 *
	 * @return void
	 */
	public function localize_block_script(): void {
		$default_data = array(
			'adminUrl'    => admin_url(),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'restUrl'     => rest_url(),
			'pluginUrl'   => plugin_dir_url( APBL_PLUGIN_FILE ),
			'socialIcons' => $this->get_social_icon_data(),
		);

		$localized_data = apply_filters( 'author_profile_blocks_localized_block_data', $default_data );

		wp_localize_script(
			'author-profile-blocks-' . $this->block_name . '-editor-script',
			'AuthorProfileBlocksData',
			$localized_data
		);
	}

	/**
	 * Get block styles based on attributes.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return array<string, string> {
	 *     CSS styles as associative array.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
	 */
	protected function get_block_styles( array $attributes ): array {
		$styles = array();

		// Background color.
		if ( ! empty( $attributes['backgroundColor'] ) ) {
			$styles['background-color'] = $attributes['backgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['padding'] ) ) {
			$styles['padding'] = $attributes['padding'] . 'px';
		} elseif ( isset( $attributes['blockPadding'] ) ) {
			$styles['padding'] = $attributes['blockPadding'] . 'px';
		}

		// Margin
		if ( isset( $attributes['margin'] ) && ! empty( $attributes['margin'] ) ) {
			$styles['margin'] = $attributes['margin'];
		}

		// Container width — profile uses width directly; other blocks expose a CSS var
		// so the block's SCSS can apply max-width + auto-centering via attribute selector.
		if ( isset( $attributes['containerWidth'] ) && ! empty( $attributes['containerWidth'] ) ) {
			if ( 'author-profile' === $this->block_name ) {
				$styles['max-width'] = $attributes['containerWidth'];
				$styles['margin-inline'] = 'auto';
			} else {
				$styles[ '--' . $this->block_name . '-container-width' ] = $attributes['containerWidth'];
			}
		}

		// Border styles
		if ( isset( $attributes['borderWidth'] ) && $attributes['borderWidth'] > 0 ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
			$styles['border-style'] = 'solid';

			if ( ! empty( $attributes['borderColor'] ) ) {
				$styles['border-color'] = $attributes['borderColor'];
			}
		}

		if ( isset( $attributes['borderRadius'] ) && $attributes['borderRadius'] > 0 ) {
			$styles['border-radius'] = $attributes['borderRadius'] . 'px';
		}

		// Box shadow
		if ( ! empty( $attributes['boxShadow'] ) ) {
			$h_offset = isset( $attributes['boxShadowHorizontal'] ) ? $attributes['boxShadowHorizontal'] : 0;
			$v_offset = isset( $attributes['boxShadowVertical'] ) ? $attributes['boxShadowVertical'] : 4;
			$blur     = isset( $attributes['boxShadowBlur'] ) ? $attributes['boxShadowBlur'] : 8;
			$spread   = isset( $attributes['boxShadowSpread'] ) ? $attributes['boxShadowSpread'] : 0;
			$color    = ! empty( $attributes['boxShadowColor'] ) ? $attributes['boxShadowColor'] : 'rgba(0,0,0,0.2)';

			$styles['box-shadow'] = $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color;
		}

		// Section spacing custom property
		if ( isset( $attributes['sectionSpacing'] ) ) {
			$styles['--author-section-spacing'] = $attributes['sectionSpacing'] . 'px';
		}

		// Animation duration
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['--author-animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform properties
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['--author-transform-scale'] = $attributes['transformScale'];
		}

		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$styles['--author-transform-rotate'] = $attributes['transformRotate'] . 'deg';
		}

		// Filter properties
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$styles['--author-filter-brightness'] = $attributes['filterBrightness'] . '%';
		}

		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$styles['--author-filter-contrast'] = $attributes['filterContrast'] . '%';
		}

		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$styles['--author-filter-saturate'] = $attributes['filterSaturate'] . '%';
		}

		// Gradient background
		if ( ! empty( $attributes['gradientBackground'] ) ) {
			$start_color = $attributes['gradientStartColor'] ?? '#ffffff';
			$end_color   = $attributes['gradientEndColor'] ?? '#f8f9fa';
			$direction   = $attributes['gradientDirection'] ?? 'to bottom';

			$styles['background'] = 'linear-gradient(' . $direction . ', ' . $start_color . ', ' . $end_color . ')';
		}

		// Custom CSS variables
		if ( isset( $attributes['customVar1'] ) && ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-custom-var-1'] = $attributes['customVar1'];
		}

		if ( isset( $attributes['customVar2'] ) && ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-custom-var-2'] = $attributes['customVar2'];
		}

		// Avatar custom properties
		if ( isset( $attributes['avatarSize'] ) ) {
			$styles['--author-avatar-size'] = $attributes['avatarSize'] . 'px';
		}

		if ( isset( $attributes['avatarBorderWidth'] ) ) {
			$styles['--author-avatar-border-width'] = $attributes['avatarBorderWidth'] . 'px';
		}

		if ( ! empty( $attributes['avatarBorderColor'] ) ) {
			$styles['--author-avatar-border-color'] = $attributes['avatarBorderColor'];
		}

		if ( ! empty( $attributes['avatarShape'] ) && $attributes['avatarShape'] === 'custom' && isset( $attributes['avatarBorderRadius'] ) ) {
			$styles['--author-avatar-border-radius'] = $attributes['avatarBorderRadius'] . 'px';
		}

		if ( ! empty( $attributes['avatarAlignment'] ) ) {
			$styles['--author-avatar-align'] = $attributes['avatarAlignment'];
			$flex_justify = array(
				'left'   => 'flex-start',
				'center' => 'center',
				'right'  => 'flex-end',
			);
			$styles['--author-avatar-justify'] = $flex_justify[ $attributes['avatarAlignment'] ] ?? 'flex-start';
		}

		if ( isset( $attributes['avatarMargin'] ) ) {
			$styles['--author-avatar-margin'] = $attributes['avatarMargin'] . 'px';
		}

		// Name custom properties
		if ( isset( $attributes['nameSize'] ) ) {
			$styles['--author-name-size'] = $attributes['nameSize'] . 'px';
		}

		if ( ! empty( $attributes['nameWeight'] ) ) {
			$styles['--author-name-weight'] = $attributes['nameWeight'];
		}

		if ( ! empty( $attributes['nameColor'] ) ) {
			$styles['--author-name-color'] = $attributes['nameColor'];
		}

		if ( ! empty( $attributes['nameTransform'] ) ) {
			$styles['--author-name-transform'] = $attributes['nameTransform'];
		}

		if ( ! empty( $attributes['nameAlignment'] ) ) {
			$styles['--author-name-align'] = $attributes['nameAlignment'];
		}

		if ( isset( $attributes['nameMargin'] ) ) {
			$styles['--author-name-margin'] = $attributes['nameMargin'] . 'px';
		}

		// Description custom properties
		if ( isset( $attributes['descriptionSize'] ) ) {
			$styles['--author-description-size'] = $attributes['descriptionSize'] . 'px';
		}

		if ( isset( $attributes['descriptionLineHeight'] ) ) {
			$styles['--author-description-line-height'] = $attributes['descriptionLineHeight'];
		}

		if ( ! empty( $attributes['descriptionColor'] ) ) {
			$styles['--author-description-color'] = $attributes['descriptionColor'];
		}

		if ( ! empty( $attributes['descriptionStyle'] ) ) {
			$styles['--author-description-style'] = $attributes['descriptionStyle'];
		}

		if ( ! empty( $attributes['descriptionAlignment'] ) ) {
			$styles['--author-description-align'] = $attributes['descriptionAlignment'];
		}

		if ( isset( $attributes['descriptionMargin'] ) ) {
			$styles['--author-description-margin'] = $attributes['descriptionMargin'] . 'px';
		}

		// Meta custom properties
		if ( isset( $attributes['metaSize'] ) ) {
			$styles['--author-meta-size'] = $attributes['metaSize'] . 'px';
		}

		if ( ! empty( $attributes['metaColor'] ) ) {
			$styles['--author-meta-color'] = $attributes['metaColor'];
		}

		if ( ! empty( $attributes['metaStyle'] ) ) {
			$styles['--author-meta-style'] = $attributes['metaStyle'];
		}

		if ( isset( $attributes['metaBold'] ) && $attributes['metaBold'] ) {
			$styles['--author-meta-weight'] = 'bold';
		}

		if ( ! empty( $attributes['metaAlignment'] ) ) {
			$styles['--author-meta-align'] = $attributes['metaAlignment'];
		}

		if ( isset( $attributes['metaMargin'] ) ) {
			$styles['--author-meta-margin'] = $attributes['metaMargin'] . 'px';
		}

		// Email link custom properties
		if ( ! empty( $attributes['emailLinkColor'] ) ) {
			$styles['--author-email-link-color'] = $attributes['emailLinkColor'];
		}

		if ( ! empty( $attributes['emailHoverColor'] ) ) {
			$styles['--author-email-link-hover-color'] = $attributes['emailHoverColor'];
		}

		// Social icon custom properties
		if ( isset( $attributes['socialIconSize'] ) ) {
			$styles['--author-social-icon-size'] = $attributes['socialIconSize'] . 'px';
		}

		if ( ! empty( $attributes['socialIconColor'] ) ) {
			$styles['--author-social-icon-color'] = $attributes['socialIconColor'];
		}

		if ( ! empty( $attributes['socialIconHoverColor'] ) ) {
			$styles['--author-social-icon-hover-color'] = $attributes['socialIconHoverColor'];
		}

		if ( ! empty( $attributes['socialIconBackground'] ) ) {
			$styles['--author-social-icon-bg'] = $attributes['socialIconBackground'];
		}

		if ( ! empty( $attributes['socialIconBackgroundHover'] ) ) {
			$styles['--author-social-icon-bg-hover'] = $attributes['socialIconBackgroundHover'];
		}

		if ( isset( $attributes['socialIconSpacing'] ) ) {
			$styles['--author-social-icon-spacing'] = $attributes['socialIconSpacing'] . 'px';
		}

		if ( ! empty( $attributes['socialIconAlignment'] ) ) {
			$styles['--author-social-icon-align'] = $attributes['socialIconAlignment'];
		}

		// More content custom properties
		if ( ! empty( $attributes['moreContentBorderColor'] ) ) {
			$styles['--author-more-content-border-color'] = $attributes['moreContentBorderColor'];
		}

		if ( isset( $attributes['moreContentPadding'] ) ) {
			$styles['--author-more-content-padding'] = $attributes['moreContentPadding'] . 'px';
		}

		// Custom CSS variables
		if ( ! empty( $attributes['customVar1'] ) ) {
			$styles['--author-profile-custom-var-1'] = $attributes['customVar1'];
		}

		if ( ! empty( $attributes['customVar2'] ) ) {
			$styles['--author-profile-custom-var-2'] = $attributes['customVar2'];
		}

		// Border color if enabled.
		if ( ! empty( $attributes['enableBorder'] ) && ! empty( $attributes['borderColor'] ) ) {
			$styles['border-color'] = $attributes['borderColor'];
		}

		// Border width if specified.
		if ( ! empty( $attributes['enableBorder'] ) && isset( $attributes['borderWidth'] ) ) {
			$styles['border-width'] = $attributes['borderWidth'] . 'px';
		}

		return $styles;
	}

	/**
	 * Get styles for individual items like list items or grid items.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 *
	 * @return array<string, string> {
	 *     CSS styles as associative array.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
	 */
	protected function get_item_styles( array $attributes ): array {
		$styles = array();

		// Item background color.
		if ( ! empty( $attributes['itemBackgroundColor'] ) ) {
			$styles['background-color'] = $attributes['itemBackgroundColor'];
		}

		// Padding.
		if ( isset( $attributes['itemPadding'] ) ) {
			$styles['padding'] = $attributes['itemPadding'] . 'px';
		}

		// Border color if dividers enabled.
		if ( ! empty( $attributes['enableDividers'] ) && ! empty( $attributes['dividerColor'] ) ) {
			$styles['border-color'] = $attributes['dividerColor'];
		}

		// Animation duration for items
		if ( isset( $attributes['animationDuration'] ) ) {
			$styles['animation-duration'] = $attributes['animationDuration'] . 'ms';
		}

		// Transform scale
		if ( isset( $attributes['transformScale'] ) && $attributes['transformScale'] !== 1 ) {
			$styles['transform'] = 'scale(' . $attributes['transformScale'] . ')';
		}

		// Transform rotate
		if ( isset( $attributes['transformRotate'] ) && $attributes['transformRotate'] !== 0 ) {
			$current_transform   = $styles['transform'] ?? '';
			$rotate_transform    = 'rotate(' . $attributes['transformRotate'] . 'deg)';
			$styles['transform'] = $current_transform ? $current_transform . ' ' . $rotate_transform : $rotate_transform;
		}

		// Filter properties
		$filters = array();
		if ( isset( $attributes['filterBrightness'] ) && $attributes['filterBrightness'] !== 100 ) {
			$filters[] = 'brightness(' . $attributes['filterBrightness'] . '%)';
		}
		if ( isset( $attributes['filterContrast'] ) && $attributes['filterContrast'] !== 100 ) {
			$filters[] = 'contrast(' . $attributes['filterContrast'] . '%)';
		}
		if ( isset( $attributes['filterSaturate'] ) && $attributes['filterSaturate'] !== 100 ) {
			$filters[] = 'saturate(' . $attributes['filterSaturate'] . '%)';
		}
		if ( ! empty( $filters ) ) {
			$styles['filter'] = implode( ' ', $filters );
		}

		return $styles;
	}

	/**
	 * Convert styles array to inline style string.
	 *
	 * @param array<string, string> $styles {
	 *     Array of CSS property:value pairs.
	 *
	 *     @type string $property CSS property name.
	 *     @type string $value    CSS property value.
	 * }
	 *
	 * @return string CSS inline style string.
	 */
	protected function get_styles_string( array $styles ): string {
		if ( empty( $styles ) ) {
			return '';
		}

		$style_strings = array();
		foreach ( $styles as $property => $value ) {
			$style_strings[] = $property . ': ' . $value;
		}

		return implode( '; ', $style_strings );
	}

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
