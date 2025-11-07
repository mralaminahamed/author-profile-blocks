<?php
/**
 * Author List Block Template
 *
 * @package AuthorProfileBlocks
 * @var array $authors Authors data
 * @var array $attributes Block attributes
 * @var Author_List_Block $block_instance Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// List classes based on style and options.
$list_classes   = array( 'apbl-authors-list-preview' );
$list_classes[] = 'apbl-display-' . ( $attributes['displayStyle'] ?? 'compact' );
$list_classes[] = 'apbl-text-align-' . ( $attributes['textAlign'] ?? 'left' );

if ( ! empty( $attributes['enableDividers'] ) ) {
	$list_classes[] = 'apbl-has-dividers';
}

$list_tag = $attributes['listStyle'] ?? 'ul';
?>
<div class="<?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
	<<?php echo $list_tag; ?> class="apbl-authors-list">
		<?php foreach ( $authors as $author ) : ?>
			<li class="apbl-author-list-item">
				<div class="apbl-author-content">
					<?php if ( ! empty( $author['avatar'] ) && ! empty( $attributes['showImage'] ) ) : ?>
						<div class="apbl-author-avatar">
							<img
								src="<?php echo esc_url( $author['avatar'] ); ?>"
								alt="<?php echo esc_attr( $author['name'] ?? $author['display_name'] ?? '' ); ?>"
								width="60"
								height="60"
							/>
						</div>
					<?php endif; ?>

					<div class="apbl-author-info">
						<h3 class="apbl-author-name">
							<?php echo esc_html( $author['name'] ?? $author['display_name'] ?? 'User ' . $author['id'] ); ?>
						</h3>

						<?php if ( ! empty( $author['position'] ) && ! empty( $attributes['showPosition'] ) ) : ?>
							<div class="apbl-author-position">
								<?php echo esc_html( $author['position'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $author['email'] ) && ! empty( $attributes['showEmail'] ) ) : ?>
							<div class="apbl-author-email">
								<a href="<?php echo esc_url( 'mailto:' . $author['email'] ); ?>">
									<?php echo esc_html( $author['email'] ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $author['description'] ) && ! empty( $attributes['showDescription'] ) && $attributes['displayStyle'] === 'detailed' ) : ?>
							<div class="apbl-author-description">
								<?php
								$description = strlen( $author['description'] ) > 150
									? substr( $author['description'], 0, 150 ) . '...'
									: $author['description'];
								echo esc_html( $description );
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $author['social'] ) && is_array( $author['social'] ) && ! empty( $attributes['showSocial'] ) ) : ?>
					<div class="apbl-author-social">
						<?php
						$social_profiles = array_filter( $author['social'] );
						foreach ( $social_profiles as $network => $url ) :
							if ( ! $url ) {
								continue;
							}
							?>
							<a
								href="<?php echo esc_url( $url ); ?>"
								class="apbl-social-<?php echo esc_attr( $network ); ?>"
								target="_blank"
								rel="noopener noreferrer"
							>
								<?php echo esc_html( $network ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</<?php echo $list_tag; ?>>
</div>