<?php
/**
 * Author Profile Block Wrapper Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $wrapper_attributes Block wrapper attributes
 * @var string $content_order Content order/layout
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 * @var string $author_email Rendered author email HTML
 * @var string $registered_date Rendered registration date HTML
 * @var string $author_description Rendered author description HTML
 * @var string $social_links Rendered social links HTML
 * @var string $more_content Optional more content HTML
 * @var Author_Profile_Block $block_instance Block instance
 */

use AuthorProfileBlocks\Blocks\Author_Profile_Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div
<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $wrapper_attributes is properly escaped
echo $wrapper_attributes;
?>
>
	<?php
	switch ( $content_order ) {
		case 'content-image':
			author_profile_blocks()->get_template(
				'blocks/author-profile/content-image.php',
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
					'block_instance'     => $block_instance,
				)
			);
			break;

		case 'image-top':
			author_profile_blocks()->get_template(
				'blocks/author-profile/image-top.php',
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
					'block_instance'     => $block_instance,
				)
			);
			break;

		case 'content-top':
			author_profile_blocks()->get_template(
				'blocks/author-profile/content-top.php',
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
					'block_instance'     => $block_instance,
				)
			);
			break;

		case 'image-content':
		default:
			author_profile_blocks()->get_template(
				'blocks/author-profile/image-content.php',
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
					'block_instance'     => $block_instance,
				)
			);
			break;
	}

	// Add optional more content if enabled.
	if ( ! empty( $more_content ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $more_content is properly escaped
		echo $more_content;
	}
	?>
</div>
