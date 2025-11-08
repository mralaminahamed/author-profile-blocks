<?php
/**
 * Admin Settings Page Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Author Profile Blocks Settings', 'author-profile-blocks' ); ?></h1>
	<p><?php esc_html_e( 'Configure the Author Profile Blocks plugin settings.', 'author-profile-blocks' ); ?></p>

	<?php settings_errors( 'author_profile_blocks_settings' ); ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'author_profile_blocks_settings' );
		do_settings_sections( 'author_profile_blocks_settings' );
		submit_button();
		?>
	</form>

	<div class="author-profile-blocks-info" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
		<h3><?php esc_html_e( 'Plugin Information', 'author-profile-blocks' ); ?></h3>
		<p><strong><?php esc_html_e( 'Version:', 'author-profile-blocks' ); ?></strong> <?php echo esc_html( APBL_VERSION ); ?></p>
		<p><strong><?php esc_html_e( 'Documentation:', 'author-profile-blocks' ); ?></strong> <a href="https://github.com/mralaminahamed/author-profile-blocks" target="_blank">GitHub Repository</a></p>
		<p><strong><?php esc_html_e( 'Support:', 'author-profile-blocks' ); ?></strong> <a href="https://github.com/mralaminahamed/author-profile-blocks/issues" target="_blank">Create an Issue</a></p>
	</div>
</div>