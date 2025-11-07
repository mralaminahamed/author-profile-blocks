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

$options = get_option( 'author_profile_blocks_settings', array() );
$selected_roles = isset( $options['author_roles'] ) ? $options['author_roles'] : array( 'administrator', 'editor', 'author' );

$roles = wp_roles()->roles;
?>
<select name="author_profile_blocks_settings[author_roles][]" multiple="multiple" class="regular-text">
	<?php foreach ( $roles as $role_key => $role ) : ?>
		<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo in_array( $role_key, $selected_roles, true ) ? 'selected' : ''; ?>>
			<?php echo esc_html( $role['name'] ); ?>
		</option>
	<?php endforeach; ?>
</select>
<p class="description"><?php esc_html_e( 'Select which user roles should be available as authors. Hold Ctrl/Cmd to select multiple roles.', 'author-profile-blocks' ); ?></p>