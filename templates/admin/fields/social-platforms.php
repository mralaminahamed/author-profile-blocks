<?php
/**
 * Social Platforms Field Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_options            = get_option( 'author_profile_blocks_settings', array() );
$apbl_selected_platforms = $apbl_options['social_platforms'] ?? array( 'facebook', 'twitter', 'linkedin', 'instagram' );

$apbl_platforms = array(
	'facebook'  => __( 'Facebook', 'author-profile-blocks' ),
	'twitter'   => __( 'Twitter/X', 'author-profile-blocks' ),
	'linkedin'  => __( 'LinkedIn', 'author-profile-blocks' ),
	'instagram' => __( 'Instagram', 'author-profile-blocks' ),
	'youtube'   => __( 'YouTube', 'author-profile-blocks' ),
	'website'   => __( 'Website', 'author-profile-blocks' ),
);
?>
<div class="social-platforms-checkboxes">
	<?php foreach ( $apbl_platforms as $apbl_platform_key => $apbl_platform_name ) : ?>
		<label style="display: block; margin-bottom: 5px;">
			<input type="checkbox" name="author_profile_blocks_settings[social_platforms][]" value="<?php echo esc_attr( $apbl_platform_key ); ?>" <?php echo in_array( $apbl_platform_key, $apbl_selected_platforms, true ) ? 'checked' : ''; ?> />
			<?php echo esc_html( $apbl_platform_name ); ?>
		</label>
	<?php endforeach; ?>
</div>
<p class="description"><?php esc_html_e( 'Select which social media platforms to display in author profiles.', 'author-profile-blocks' ); ?></p>
