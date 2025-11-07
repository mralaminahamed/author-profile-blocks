<?php
/**
 * Cache Duration Field Template
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options = get_option( 'author_profile_blocks_settings', array() );
$cache_duration = isset( $options['cache_duration'] ) ? $options['cache_duration'] : 24;
?>
<input type="number" name="author_profile_blocks_settings[cache_duration]" value="<?php echo esc_attr( $cache_duration ); ?>" min="1" max="168" class="small-text" />
<p class="description"><?php esc_html_e( 'How long to cache author data in hours (1-168). Default is 24 hours.', 'author-profile-blocks' ); ?></p>