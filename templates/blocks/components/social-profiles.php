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

$apbl_classes = 'apbl-social-profiles';
if ( ! empty( $additional_class ) ) {
	$apbl_classes .= ' ' . esc_attr( $additional_class );
}

// Get social icon alignment if available
if ( ! empty( $social_profiles['socialIconAlignment'] ) ) {
	$apbl_classes .= ' apbl-social-align-' . esc_attr( $social_profiles['socialIconAlignment'] );
}

// Generate styles for icons
$apbl_icon_styles = array();

if ( ! empty( $social_profiles['socialIconSize'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-size: ' . esc_attr( $social_profiles['socialIconSize'] ) . 'px';
}

if ( ! empty( $social_profiles['socialIconColor'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-color: ' . esc_attr( $social_profiles['socialIconColor'] );
}

if ( ! empty( $social_profiles['socialIconHoverColor'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-hover-color: ' . esc_attr( $social_profiles['socialIconHoverColor'] );
}

if ( ! empty( $social_profiles['socialIconBackground'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-bg: ' . esc_attr( $social_profiles['socialIconBackground'] );
}

if ( ! empty( $social_profiles['socialIconBackgroundHover'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-bg-hover: ' . esc_attr( $social_profiles['socialIconBackgroundHover'] );
}

if ( ! empty( $social_profiles['socialIconSpacing'] ) ) {
	$apbl_icon_styles[] = '--author-social-icon-spacing: ' . esc_attr( $social_profiles['socialIconSpacing'] ) . 'px';
}

// Build style attribute — values already esc_attr'd above
$apbl_style_html = ! empty( $apbl_icon_styles ) ? ' style="' . implode( '; ', $apbl_icon_styles ) . '"' : '';

// Get social icons mapping
$apbl_social_icons = array(
	'facebook'  => 'dashicons-facebook',
	'twitter'   => 'dashicons-twitter',
	'linkedin'  => 'dashicons-linkedin',
	'instagram' => 'dashicons-instagram',
	'youtube'   => 'dashicons-video-alt3',
	'github'    => 'dashicons-editor-code',
	'website'   => 'dashicons-admin-site',
);

// Filter profiles to show
$apbl_filtered_profiles = array();
if ( ! empty( $show_profiles ) ) {
	foreach ( $social_profiles as $apbl_network => $apbl_url ) {
		if ( $apbl_network !== 'socialIconSize' &&
			$apbl_network !== 'socialIconColor' &&
			$apbl_network !== 'socialIconHoverColor' &&
			$apbl_network !== 'socialIconBackground' &&
			$apbl_network !== 'socialIconBackgroundHover' &&
			$apbl_network !== 'socialIconSpacing' &&
			$apbl_network !== 'socialIconAlignment' &&
			in_array( $apbl_network, $show_profiles, true ) ) {
			$apbl_filtered_profiles[ $apbl_network ] = $apbl_url;
		}
	}
} else {
	// Filter out the style properties from profiles
	foreach ( $social_profiles as $apbl_network => $apbl_url ) {
		if ( $apbl_network !== 'socialIconSize' &&
			$apbl_network !== 'socialIconColor' &&
			$apbl_network !== 'socialIconHoverColor' &&
			$apbl_network !== 'socialIconBackground' &&
			$apbl_network !== 'socialIconBackgroundHover' &&
			$apbl_network !== 'socialIconSpacing' &&
			$apbl_network !== 'socialIconAlignment' ) {
			$apbl_filtered_profiles[ $apbl_network ] = $apbl_url;
		}
	}
}
?>
<div class="<?php echo esc_attr( $apbl_classes ); ?>"
<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values already esc_attr'd
	echo $apbl_style_html;
?>
	>
	<ul class="apbl-social-list">
		<?php foreach ( $apbl_filtered_profiles as $apbl_network => $apbl_url ) : ?>
			<?php if ( ! empty( $apbl_url ) && isset( $apbl_social_icons[ $apbl_network ] ) ) : ?>
				<li class="apbl-social-item apbl-social-<?php echo esc_attr( $apbl_network ); ?>">
					<a href="<?php echo esc_url( $apbl_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="dashicons <?php echo esc_attr( $apbl_social_icons[ $apbl_network ] ); ?>" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php echo esc_html( ucfirst( $apbl_network ) ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>