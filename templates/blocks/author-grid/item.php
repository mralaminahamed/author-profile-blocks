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
?>
<div class="<?php echo $item_class; ?>"<?php echo $style_attribute; ?>>
	<?php
	// Use the appropriate layout template based on the selected layout.
	switch ( $layout ) {
		case 'compact':
			include __DIR__ . '/layouts/compact.php';
			break;

		case 'centered':
			include __DIR__ . '/layouts/centered.php';
			break;

		case 'card':
		default:
			include __DIR__ . '/layouts/card.php';
			break;
	}
	?>
</div>
