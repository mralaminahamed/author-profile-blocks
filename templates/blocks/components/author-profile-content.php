<?php
/**
 * Common Author Profile Content Template
 *
 * Renders the author image and text fields. Layout order is controlled via
 * CSS classes on the parent wrapper (.content-order-image-content, etc.).
 *
 * @package AuthorProfileBlocks
 * @var array  $author              Author data
 * @var array  $attributes          Block attributes
 * @var string $author_image        Rendered author image HTML
 * @var string $author_name         Rendered author name HTML
 * @var string $author_position     Rendered author position HTML
 * @var string $author_email        Rendered author email HTML
 * @var string $registered_date     Rendered registration date HTML
 * @var string $author_description  Rendered author description HTML
 * @var string $social_links        Rendered social links HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Build container class — must match profile/style.scss selectors
$apbl_container_class = 'apbl-author-profile-content';

if ( ! empty( $attributes['googleFont'] ) ) {
	$apbl_container_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

// Build inline styles — use the same CSS vars referenced in profile/style.scss
$apbl_container_style = '';
?>
<div class="<?php echo esc_attr( $apbl_container_class ); ?>"<?php echo ! empty( $apbl_container_style ) ? ' style="' . esc_attr( $apbl_container_style ) . '"' : ''; ?>>

	<?php if ( ! empty( $author_image ) ) : ?>
		<div class="apbl-author-image">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $author_image;
			?>
		</div>
	<?php endif; ?>

	<div class="apbl-author-info">
		<?php if ( ! empty( $author_name ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $author_name;
			?>
		<?php endif; ?>

		<?php if ( ! empty( $author_position ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $author_position;
			?>
		<?php endif; ?>

		<?php if ( ! empty( $author_email ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $author_email;
			?>
		<?php endif; ?>

		<?php if ( ! empty( $author_description ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $author_description;
			?>
		<?php endif; ?>

		<?php if ( ! empty( $registered_date ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $registered_date;
			?>
		<?php endif; ?>

		<?php if ( ! empty( $social_links ) ) : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-escaped
			echo $social_links;
			?>
		<?php endif; ?>
	</div>

</div>
