<?php
/**
 * Render the Author Profile block on the frontend.
 *
 * @package WPAuthorShowcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get block attributes.
 *
 * @var array $attributes Block attributes.
 */
$author_id = isset( $attributes['authorId'] ) ? (int) $attributes['authorId'] : 0;
$show_image = ! isset( $attributes['showImage'] ) || (bool) $attributes['showImage'];
$show_email = ! isset( $attributes['showEmail'] ) || (bool) $attributes['showEmail'];
$show_description = ! isset( $attributes['showDescription'] ) || (bool) $attributes['showDescription'];
$show_more = isset( $attributes['showMore'] ) && (bool) $attributes['showMore'];
$more_content = $attributes['moreContent'] ?? '';
$background_color = $attributes['backgroundColor'] ?? '#ffffff';
$text_align = $attributes['textAlign'] ?? 'left';
$padding = isset( $attributes['padding'] ) ? (int) $attributes['padding'] : 20;

// Generate CSS styles.
$wrapper_style = sprintf(
	'background-color: %s; text-align: %s; padding: %dpx;',
	esc_attr( $background_color ),
	esc_attr( $text_align ),
	esc_attr( $padding )
);

// If no author is selected, show an error message.
if ( ! $author_id ) {
	printf(
		'<div %s><p>%s</p></div>',
		get_block_wrapper_attributes( array( 'style' => $wrapper_style ) ),
		esc_html__( 'Please select an author profile to display.', 'wp-author-showcase' )
	);
	return;
}

// Get author data.
$author = get_post( $author_id );
if ( ! $author || 'author_profile' !== $author->post_type ) {
	printf(
		'<div %s><p>%s</p></div>',
		get_block_wrapper_attributes( array( 'style' => $wrapper_style ) ),
		esc_html__( 'Author profile not found.', 'wp-author-showcase' )
	);
	return;
}

// Get author meta.
$email = get_post_meta( $author_id, 'wpas_author_email', true );
$description = get_post_meta( $author_id, 'wpas_author_description', true );
$featured_image = get_the_post_thumbnail_url( $author_id, 'medium' );

// Render the block.
?>
<div <?php echo get_block_wrapper_attributes( array( 'style' => $wrapper_style ) ); ?>>
	<div class="wpas-author-profile-content">
		<?php if ( $show_image && $featured_image ) : ?>
			<div class="wpas-author-image">
				<img
					src="<?php echo esc_url( $featured_image ); ?>"
					alt="<?php echo esc_attr( $author->post_title ); ?>"
				/>
			</div>
		<?php endif; ?>

		<div class="wpas-author-info">
			<h3 class="wpas-author-name">
				<?php echo esc_html( $author->post_title ); ?>
			</h3>

			<?php if ( $show_email && $email ) : ?>
				<div class="wpas-author-email">
					<a href="mailto:<?php echo esc_attr( $email ); ?>">
						<?php echo esc_html( $email ); ?>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( $show_description && $description ) : ?>
				<div class="wpas-author-description">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $show_more && ! empty( $more_content ) ) : ?>
		<div class="wpas-author-more-content">
			<?php echo wp_kses_post( $more_content ); ?>
		</div>
	<?php endif; ?>
</div>
