<?php
/**
 * Author Grid Item Template
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
 * @var Author_Grid_Block $block_instance Block instance
 */

use AuthorProfileBlocks\Blocks\Author_Grid_Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Use the common item template
author_profile_blocks()->get_template(
	'blocks/components/author-item.php',
	array(
		'author'             => $author,
		'attributes'         => $attributes,
		'item_class'         => $item_class,
		'style_attribute'    => $style_attribute,
		'layout'             => $layout,
		'author_image'       => $author_image,
		'author_name'        => $author_name,
		'author_position'    => $author_position,
		'author_email'       => $author_email,
		'registered_date'    => $registered_date,
		'author_description' => $author_description,
		'social_links'       => $social_links,
		'wrapper_element'    => 'div',
		'wrapper_class'      => '',
	)
);
