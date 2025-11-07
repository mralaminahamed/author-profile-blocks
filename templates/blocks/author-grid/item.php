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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Prepare template variables for the layout templates
$layout_vars = array(
	'author'              => $author,
	'attributes'          => $attributes,
	'author_image'        => $author_image,
	'author_name'         => $author_name,
	'author_position'     => $author_position,
	'author_email'        => $author_email,
	'author_description'  => $author_description,
	'registered_date'     => $registered_date,
	'social_links'        => $social_links,
);
?>
<div class="<?php echo $item_class; ?>"<?php echo $style_attribute; ?>>
	<?php
	// Use the shared layout templates
	switch ( $layout ) {
		case 'compact':
			include dirname( __DIR__, 2 ) . '/layouts/compact.php';
			break;

		case 'centered':
			include dirname( __DIR__, 2 ) . '/layouts/centered.php';
			break;

		case 'card':
		default:
			include dirname( __DIR__, 2 ) . '/layouts/card.php';
			break;
	}
	?>
</div>
