<?php
/**
 * Author Roles Field Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apbl_selected_roles = author_profile_blocks()->settings->get_enabled_author_roles();

$apbl_roles = wp_roles()->roles;
?>
<select name="author_profile_blocks_settings[author_roles][]" multiple="multiple" class="regular-text">
	<?php foreach ( $apbl_roles as $apbl_role_key => $apbl_role ) : ?>
		<option value="<?php echo esc_attr( $apbl_role_key ); ?>" <?php echo in_array( $apbl_role_key, $apbl_selected_roles, true ) ? 'selected' : ''; ?>>
			<?php echo esc_html( $apbl_role['name'] ); ?>
		</option>
	<?php endforeach; ?>
</select>
<p class="description"><?php esc_html_e( 'Select which user roles should be available as authors. Hold Ctrl/Cmd to select multiple roles.', 'author-profile-blocks' ); ?></p>
