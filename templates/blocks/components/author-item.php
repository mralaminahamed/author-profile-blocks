<?php
/**
 * Common Author Item Template (used by grid and carousel blocks)
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $item_class Pre-rendered item CSS classes
 * @var string $style_attribute Pre-rendered style attribute
 * @var string $layout Layout type (card, compact, centered)
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 * @var string $author_email Rendered author email HTML
 * @var string $registered_date Rendered registration date HTML
 * @var string $author_description Rendered author description HTML
 * @var string $social_links Rendered social links HTML
 * @var string $wrapper_element HTML element to wrap the item (div, li, etc.)
 * @var string $wrapper_class CSS class for the wrapper element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Default wrapper element and class
$apbl_wrapper_element = $wrapper_element ?? 'div';
$apbl_wrapper_class   = $wrapper_class ?? '';
?>
<<?php echo esc_attr( $apbl_wrapper_element ); ?><?php echo ! empty( $apbl_wrapper_class ) ? ' class="' . esc_attr( $apbl_wrapper_class ) . '"' : ''; ?>>
	<div class="<?php echo esc_attr( $item_class ); ?>"<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- style value already esc_attr'd in block class
	echo $style_attribute; ?>>
		<?php
		// Use the shared layout templates
		switch ( $layout ) {
			case 'minimal':
				author_profile_blocks()->get_template(
					'blocks/layouts/minimal.php',
					array(
						'author'     => $author,
						'attributes' => $attributes,
					)
				);
				break;

			case 'compact':
				author_profile_blocks()->get_template(
					'blocks/layouts/compact.php',
					array(
						'author'             => $author,
						'attributes'         => $attributes,
						'author_image'       => $author_image,
						'author_name'        => $author_name,
						'author_position'    => $author_position,
						'author_email'       => $author_email,
						'author_description' => $author_description,
						'social_links'       => $social_links,
					)
				);
				break;

			case 'centered':
				author_profile_blocks()->get_template(
					'blocks/layouts/centered.php',
					array(
						'author'             => $author,
						'attributes'         => $attributes,
						'author_image'       => $author_image,
						'author_name'        => $author_name,
						'author_position'    => $author_position,
						'author_email'       => $author_email,
						'registered_date'    => $registered_date,
						'author_description' => $author_description,
						'social_links'       => $social_links,
					)
				);
				break;

			case 'card':
			default:
				author_profile_blocks()->get_template(
					'blocks/layouts/card.php',
					array(
						'author'             => $author,
						'attributes'         => $attributes,
						'author_image'       => $author_image,
						'author_name'        => $author_name,
						'author_position'    => $author_position,
						'author_email'       => $author_email,
						'registered_date'    => $registered_date,
						'author_description' => $author_description,
						'social_links'       => $social_links,
					)
				);
				break;
		}
		?>
	</div>
</<?php echo esc_attr( $apbl_wrapper_element ); ?>>
