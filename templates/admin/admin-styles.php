<?php
/**
 * Template for admin styles
 *
 * @package WPAuthorShowcase
 * @subpackage Templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
	/* Column styles */
	.column-author_image {
		width: 80px;
	}
	.column-author_email {
		width: 20%;
	}
	.column-author_description {
		width: 30%;
	}
	
	/* Meta field styles */
	.wpas-meta-field {
		margin-bottom: 15px;
	}
	.wpas-meta-field label {
		display: block;
		margin-bottom: 5px;
		font-weight: 600;
	}
	.wpas-meta-field input,
	.wpas-meta-field textarea {
		width: 100%;
		padding: 8px;
		border: 1px solid #ddd;
		border-radius: 4px;
	}
	
	/* Admin table styles */
	.widefat .column-author_email a {
		color: #2271b1;
		text-decoration: none;
	}
	.widefat .column-author_email a:hover {
		color: #135e96;
		text-decoration: underline;
	}
	.author-email-wrapper {
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.no-email, .no-description, .no-image {
		color: #999;
	}
	
	/* Image column styles */
	.author-image-wrapper {
		width: 60px;
		height: 60px;
		overflow: hidden;
		border-radius: 4px;
	}
	.author-thumbnail {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}
	
	.author-description-wrapper {
		position: relative;
	}
	.description-text {
		color: #50575e;
		font-size: 13px;
		line-height: 1.5;
		display: block;
	}
	.description-meta {
		margin-top: 4px;
		font-size: 11px;
		color: #999;
	}
	.description-count {
		background: #f0f0f1;
		padding: 1px 5px;
		border-radius: 3px;
		font-size: 11px;
	}
</style> 