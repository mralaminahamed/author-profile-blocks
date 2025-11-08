<?php
/**
 * User Profile Fields Template
 *
 * Template for displaying author profile fields in the WordPress user profile page.
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 *
 * @var string $description        The author's description/bio text.
 * @var string $position           The author's position or title.
 * @var array  $social_profiles    Array of social media profile URLs.
 * @var string $member_since_label Custom label for member since date display.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2><?php esc_html_e( 'Author Profile Information', 'author-profile-blocks' ); ?></h2>
<p><?php esc_html_e( 'These fields are used by the Author Profile Blocks plugin to display author information on your site.', 'author-profile-blocks' ); ?></p>

<table class="form-table" role="presentation">
	<tr class="apbl-meta-field">
		<th><label for="apbl_author_position"><?php esc_html_e( 'Position/Title', 'author-profile-blocks' ); ?></label></th>
		<td>
			<input type="text" name="apbl_author_position" id="apbl_author_position" value="<?php echo esc_attr( $position ); ?>" class="regular-text"/>
			<p class="description"><?php esc_html_e( 'Enter the author\'s position or title (e.g., "Senior Editor", "Lead Developer", etc.)', 'author-profile-blocks' ); ?></p>
		</td>
	</tr>

	<tr class="apbl-meta-field">
		<th><label for="apbl_member_since_label"><?php esc_html_e( 'Member Since Label', 'author-profile-blocks' ); ?></label></th>
		<td>
			<input type="text" name="apbl_member_since_label" id="apbl_member_since_label" value="<?php echo esc_attr( $member_since_label ); ?>" class="regular-text"/>
			<p class="description"><?php esc_html_e( 'Customize the label used for showing registration date (e.g., "Member since", "Joined on", "With us since", etc.)', 'author-profile-blocks' ); ?></p>
		</td>
	</tr>

	<tr class="apbl-meta-field">
		<th><label for="apbl_author_description"><?php esc_html_e( 'Author Description', 'author-profile-blocks' ); ?></label></th>
		<td>
			<?php
			wp_editor(
				$description,
				'apbl_author_description',
				array(
					'media_buttons' => false,
					'textarea_name' => 'apbl_author_description',
					'textarea_rows' => 5,
					'teeny'         => true,
				)
			);
			?>
			<p class="description"><?php esc_html_e( 'Enter a detailed description for this author.', 'author-profile-blocks' ); ?></p>
		</td>
	</tr>

	<tr class="apbl-meta-field">
		<th><label><?php esc_html_e( 'Social Media Profiles', 'author-profile-blocks' ); ?></label></th>
		<td>
			<div class="apbl-social-profiles">
				<p>
					<label for="apbl_social_facebook"><?php esc_html_e( 'Facebook URL', 'author-profile-blocks' ); ?></label><br/>
					<input type="url" name="apbl_social_profiles[facebook]" id="apbl_social_facebook" value="<?php echo esc_url( $social_profiles['facebook'] ?? '' ); ?>" class="regular-text"/>
				</p>
				<p>
					<label for="apbl_social_twitter"><?php esc_html_e( 'Twitter URL', 'author-profile-blocks' ); ?></label><br/>
					<input type="url" name="apbl_social_profiles[twitter]" id="apbl_social_twitter" value="<?php echo esc_url( $social_profiles['twitter'] ?? '' ); ?>" class="regular-text"/>
				</p>
				<p>
					<label for="apbl_social_linkedin"><?php esc_html_e( 'LinkedIn URL', 'author-profile-blocks' ); ?></label><br/>
					<input type="url" name="apbl_social_profiles[linkedin]" id="apbl_social_linkedin" value="<?php echo esc_url( $social_profiles['linkedin'] ?? '' ); ?>" class="regular-text"/>
				</p>
				<p>
					<label for="apbl_social_instagram"><?php esc_html_e( 'Instagram URL', 'author-profile-blocks' ); ?></label><br/>
					<input type="url" name="apbl_social_profiles[instagram]" id="apbl_social_instagram" value="<?php echo esc_url( $social_profiles['instagram'] ?? '' ); ?>" class="regular-text"/>
				</p>
				<p>
					<label for="apbl_social_website"><?php esc_html_e( 'Personal Website', 'author-profile-blocks' ); ?></label><br/>
					<input type="url" name="apbl_social_profiles[website]" id="apbl_social_website" value="<?php echo esc_url( $social_profiles['website'] ?? '' ); ?>" class="regular-text"/>
				</p>
			</div>
		</td>
	</tr>
</table>