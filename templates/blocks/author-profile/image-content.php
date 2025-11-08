<?php
/**
 * Author Profile Block - Image Content Layout Template
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Use the common profile content template
author_profile_blocks()->get_template(
	'blocks/components/author-profile-content.php',
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
		'layout_type'        => 'image-content',
	)
);
