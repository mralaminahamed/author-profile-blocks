<?php
/**
 * Template for the email column in the admin list
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 *
 * @var string $email The author's email address
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! empty( $email ) ) : ?>
	<div class="author-email-wrapper">
		<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
	</div>
<?php else : ?>
	<span class="no-email">—</span>
<?php endif; ?>
