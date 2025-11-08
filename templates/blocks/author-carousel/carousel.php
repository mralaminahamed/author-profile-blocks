<?php
/**
 * Author Carousel Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_Carousel_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Generate a simple placeholder in the editor instead of trying to initialize Slick
// The real carousel will be rendered on the frontend
if ( ! empty( $authors ) ) :
	$apbl_slides_to_show = $attributes['slidesToShow'] ?? 3;
	$apbl_max_slides     = min( $apbl_slides_to_show, count( $authors ) );
	?>
<div class="apbl-carousel-placeholder">
	<?php for ( $apbl_i = 0; $apbl_i < $apbl_max_slides; $apbl_i++ ) : ?>
		<div class="apbl-carousel-slide-placeholder">
			<?php if ( ! empty( $attributes['showImage'] ) ) : ?>
				<div class="apbl-placeholder-image"></div>
			<?php endif; ?>
			<div class="apbl-placeholder-line apbl-placeholder-title"></div>
			<?php if ( ! empty( $attributes['showPosition'] ) ) : ?>
				<div class="apbl-placeholder-line"></div>
			<?php endif; ?>
			<?php if ( ! empty( $attributes['showDescription'] ) ) : ?>
				<div class="apbl-placeholder-line apbl-placeholder-text"></div>
				<div class="apbl-placeholder-line apbl-placeholder-text"></div>
				<div class="apbl-placeholder-line apbl-placeholder-text"></div>
			<?php endif; ?>
		</div>
	<?php endfor; ?>
	<div class="apbl-editor-note">
		<em>
			<?php esc_html_e( 'Carousel preview - will be fully functional on the frontend', 'author-profile-blocks' ); ?>
		</em>
	</div>
</div>
<?php else : ?>
<div class="apbl-carousel-placeholder">
	<p><?php esc_html_e( 'No authors selected for carousel.', 'author-profile-blocks' ); ?></p>
</div>
<?php endif; ?>