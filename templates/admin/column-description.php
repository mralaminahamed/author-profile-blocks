<?php
/**
 * Template for the description column in the admin list
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 *
 * @var string $truncated       The truncated description text
 * @var int    $original_length The original description length
 * @var bool   $is_truncated    Whether the description was truncated
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! empty( $truncated ) ) : ?>
	<div class="author-description-wrapper">
		<span class="description-text"><?php echo esc_html( $truncated ); ?></span>

		<?php if ( isset( $is_truncated, $original_length ) && $is_truncated && $original_length > 0 ) : ?>
			<div class="description-meta">
				<span class="description-count">
					<?php
					printf(
						/* translators: %d: number of characters */
						esc_html__( '%d characters', 'wp-author-showcase' ),
						$original_length
					);
					?>
				</span>
			</div>
		<?php endif; ?>
	</div>
<?php else : ?>
	<span class="no-description">—</span>
<?php endif; ?>
