<?php
/**
 * Template for author description field in the meta box
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 *
 * @var string $description The author's description
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpas-meta-field" style="margin-top: 15px;">
	<label for="wpas_author_description"><?php esc_html_e( 'Description', 'wp-author-showcase' ); ?>:</label>
	<?php
		wp_editor(
			$description,
			'wpas_author_description',
			array(
				'media_buttons' => false,
				'textarea_name' => 'wpas_author_description',
				'textarea_rows' => 5,
				'teeny'         => true,
			)
		);
	?>
</div>
