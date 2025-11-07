<?php
/**
 * Social Profiles Component Template
 *
 * @package AuthorProfileBlocks
 * @var array $social_profiles Social profiles data (may include style properties)
 * @var string $additional_class Additional CSS class
 * @var array $show_profiles Optional. List of specific profiles to show
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = 'apbl-social-profiles';
if ( ! empty( $additional_class ) ) {
	$classes .= ' ' . esc_attr( $additional_class );
}

// Get social icon alignment if available
if ( ! empty( $social_profiles['socialIconAlignment'] ) ) {
	$classes .= ' apbl-social-align-' . esc_attr( $social_profiles['socialIconAlignment'] );
}

// Generate styles for icons
$icon_styles = array();

if ( ! empty( $social_profiles['socialIconSize'] ) ) {
	$icon_styles[] = '--author-social-icon-size: ' . esc_attr( $social_profiles['socialIconSize'] ) . 'px';
}

if ( ! empty( $social_profiles['socialIconColor'] ) ) {
	$icon_styles[] = '--author-social-icon-color: ' . esc_attr( $social_profiles['socialIconColor'] );
}

if ( ! empty( $social_profiles['socialIconHoverColor'] ) ) {
	$icon_styles[] = '--author-social-icon-hover-color: ' . esc_attr( $social_profiles['socialIconHoverColor'] );
}

if ( ! empty( $social_profiles['socialIconBackground'] ) ) {
	$icon_styles[] = '--author-social-icon-bg: ' . esc_attr( $social_profiles['socialIconBackground'] );
}

if ( ! empty( $social_profiles['socialIconBackgroundHover'] ) ) {
	$icon_styles[] = '--author-social-icon-bg-hover: ' . esc_attr( $social_profiles['socialIconBackgroundHover'] );
}

if ( ! empty( $social_profiles['socialIconSpacing'] ) ) {
	$icon_styles[] = '--author-social-icon-spacing: ' . esc_attr( $social_profiles['socialIconSpacing'] ) . 'px';
}

// Build style attribute
$style_html = ! empty( $icon_styles ) ? ' style="' . implode( '; ', $icon_styles ) . '"' : '';

// Get social icons mapping
$social_icons = array(
	'facebook'  => 'dashicons-facebook',
	'twitter'   => 'dashicons-twitter',
	'linkedin'  => 'dashicons-linkedin',
	'instagram' => 'dashicons-instagram',
	'website'   => 'dashicons-admin-site',
);

// Filter profiles to show
$filtered_profiles = array();
if ( ! empty( $show_profiles ) ) {
	foreach ( $social_profiles as $network => $url ) {
		if ( $network !== 'socialIconSize' &&
			$network !== 'socialIconColor' &&
			$network !== 'socialIconHoverColor' &&
			$network !== 'socialIconBackground' &&
			$network !== 'socialIconBackgroundHover' &&
			$network !== 'socialIconSpacing' &&
			$network !== 'socialIconAlignment' &&
			in_array( $network, $show_profiles, true ) ) {
			$filtered_profiles[ $network ] = $url;
		}
	}
} else {
	// Filter out the style properties from profiles
	foreach ( $social_profiles as $network => $url ) {
		if ( $network !== 'socialIconSize' &&
			$network !== 'socialIconColor' &&
			$network !== 'socialIconHoverColor' &&
			$network !== 'socialIconBackground' &&
			$network !== 'socialIconBackgroundHover' &&
			$network !== 'socialIconSpacing' &&
			$network !== 'socialIconAlignment' ) {
			$filtered_profiles[ $network ] = $url;
		}
	}
}
?>
<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $style_html; ?>>
	<ul class="apbl-social-list">
		<?php foreach ( $filtered_profiles as $network => $url ) : ?>
			<?php if ( ! empty( $url ) && isset( $social_icons[ $network ] ) ) : ?>
				<li class="apbl-social-item apbl-social-<?php echo esc_attr( $network ); ?>">
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="dashicons <?php echo esc_attr( $social_icons[ $network ] ); ?>" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php echo esc_html( ucfirst( $network ) ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>