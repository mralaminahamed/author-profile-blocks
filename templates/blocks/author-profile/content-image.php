<?php
/**
 * Author Profile Block - Content Image Layout Template
 *
 * @package AuthorProfileBlocks
 * @var array $author Author data
 * @var array $attributes Block attributes
 * @var string $author_image Rendered author image HTML
 * @var string $author_name Rendered author name HTML
 * @var string $author_position Rendered author position HTML
 * @var string $author_email Rendered author email HTML
 * @var string $registered_date Rendered registration date HTML
 * @var string $author_description Rendered author description HTML
 * @var string $social_links Rendered social links HTML
 * @var Author_Profile_Block $block_instance Block instance
 */

use AuthorProfileBlocks\Blocks\Author_Profile_Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Content Image Layout: Content on left, image on right
?>
<div class="apbl-author-profile-layout apbl-layout-content-image">
	<div class="apbl-author-profile-content-section">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $author_name is properly escaped
		echo $author_name;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $author_position is properly escaped
		echo $author_position;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $author_email is properly escaped
		echo $author_email;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $registered_date is properly escaped
		echo $registered_date;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $author_description is properly escaped
		echo $author_description;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $social_links is properly escaped
		echo $social_links;
		?>
	</div>
	<div class="apbl-author-profile-image-section">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $author_image is properly escaped
		echo $author_image;
		?>
	</div>
</div>
