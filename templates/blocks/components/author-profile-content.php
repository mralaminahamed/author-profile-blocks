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

// Build container class
$apbl_container_class = 'apb-author-profile-content';

if ( ! empty( $attributes['layoutPreset'] ) ) {
	$apbl_container_class .= ' ' . esc_attr( $attributes['layoutPreset'] );
}
if ( ! empty( $attributes['animationType'] ) && 'none' !== $attributes['animationType'] ) {
	$apbl_container_class .= ' apb-animation-' . esc_attr( $attributes['animationType'] );
}
if ( ! empty( $attributes['hoverEffect'] ) && 'none' !== $attributes['hoverEffect'] ) {
	$apbl_container_class .= ' apb-hover-' . esc_attr( $attributes['hoverEffect'] );
}
if ( ! empty( $attributes['googleFont'] ) ) {
	$apbl_container_class .= ' has-' . esc_attr( sanitize_title( $attributes['googleFont'] ) ) . '-font';
}

// Build inline styles
$apbl_container_style = '';
if ( isset( $attributes['sectionSpacing'] ) ) {
	$apbl_container_style .= '--author-profile-section-spacing: ' . (int) $attributes['sectionSpacing'] . 'px;';
}
if ( ! empty( $attributes['customVar1'] ) ) {
	$apbl_container_style .= '--author-profile-custom-var-1: ' . esc_attr( $attributes['customVar1'] ) . ';';
}
if ( ! empty( $attributes['customVar2'] ) ) {
	$apbl_container_style .= '--author-profile-custom-var-2: ' . esc_attr( $attributes['customVar2'] ) . ';';
}
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
