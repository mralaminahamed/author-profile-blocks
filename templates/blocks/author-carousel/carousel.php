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
	$slides_to_show = $attributes['slidesToShow'] ?? 3;
	$max_slides     = min( $slides_to_show, count( $authors ) );
	?>
<div class="apb-carousel-placeholder">
	<?php for ( $i = 0; $i < $max_slides; $i++ ) : ?>
		<div class="apb-carousel-slide-placeholder">
			<?php if ( ! empty( $attributes['showImage'] ) ) : ?>
				<div class="apb-placeholder-image"></div>
			<?php endif; ?>
			<div class="apb-placeholder-line apb-placeholder-title"></div>
			<?php if ( ! empty( $attributes['showPosition'] ) ) : ?>
				<div class="apb-placeholder-line"></div>
			<?php endif; ?>
			<?php if ( ! empty( $attributes['showDescription'] ) ) : ?>
				<div class="apb-placeholder-line apb-placeholder-text"></div>
				<div class="apb-placeholder-line apb-placeholder-text"></div>
				<div class="apb-placeholder-line apb-placeholder-text"></div>
			<?php endif; ?>
		</div>
	<?php endfor; ?>
	<div class="apb-editor-note">
		<em>
			<?php esc_html_e( 'Carousel preview - will be fully functional on the frontend', 'author-profile-blocks' ); ?>
		</em>
	</div>
</div>
<?php else : ?>
<div class="apb-carousel-placeholder">
	<p><?php esc_html_e( 'No authors selected for carousel.', 'author-profile-blocks' ); ?></p>
</div>
<?php endif; ?>