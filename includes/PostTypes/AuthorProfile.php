<?php
/**
 * Author Profile custom post type class
 *
 * @package WPAuthorShowcase
 * @subpackage PostTypes
 */

namespace WPAuthorShowcase\PostTypes;

use WP_Post;
use function __;
use function _x;
use function add_action;
use function add_meta_box;
use function current_user_can;
use function esc_attr;
use function esc_html_e;
use function get_post_meta;
use function register_post_meta;
use function register_post_type;
use function sanitize_email;
use function sanitize_text_field;
use function update_post_meta;
use function wp_editor;
use function wp_kses_post;
use function wp_nonce_field;
use function wp_unslash;
use function wp_verify_nonce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile custom post type.
 */
class AuthorProfile {
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	private string $post_type = 'author_profile';

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'init', [ $this, 'register_meta_fields' ] );

		// Legacy code support - will be removed in future versions.
		add_action( 'add_meta_boxes', [ $this, 'register_legacy_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_legacy_meta_boxes' ] );
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		$labels = [
			'name'               => _x( 'Author Profiles', 'post type general name', 'wp-author-showcase' ),
			'singular_name'      => _x( 'Author Profile', 'post type singular name', 'wp-author-showcase' ),
			'menu_name'          => _x( 'Author Profiles', 'admin menu', 'wp-author-showcase' ),
			'name_admin_bar'     => _x( 'Author Profile', 'add new on admin bar', 'wp-author-showcase' ),
			'add_new'            => _x( 'Add New', 'author profile', 'wp-author-showcase' ),
			'add_new_item'       => __( 'Add New Author Profile', 'wp-author-showcase' ),
			'new_item'           => __( 'New Author Profile', 'wp-author-showcase' ),
			'edit_item'          => __( 'Edit Author Profile', 'wp-author-showcase' ),
			'view_item'          => __( 'View Author Profile', 'wp-author-showcase' ),
			'all_items'          => __( 'All Author Profiles', 'wp-author-showcase' ),
			'search_items'       => __( 'Search Author Profiles', 'wp-author-showcase' ),
			'parent_item_colon'  => __( 'Parent Author Profiles:', 'wp-author-showcase' ),
			'not_found'          => __( 'No author profiles found.', 'wp-author-showcase' ),
			'not_found_in_trash' => __( 'No author profiles found in Trash.', 'wp-author-showcase' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'author-profile' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-id',
			'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
			'show_in_rest'       => true,
		];

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register custom meta fields.
	 *
	 * @return void
	 */
	public function register_meta_fields(): void {
		// Register email field.
		register_post_meta(
			$this->post_type,
			'wpas_author_email',
			[
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_email',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);

		// Register description field.
		register_post_meta(
			$this->post_type,
			'wpas_author_description',
			[
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);
	}

	/**
	 * Register meta boxes using legacy approach.
	 * Will be removed in future versions.
	 *
	 * @return void
	 */
	public function register_legacy_meta_boxes(): void {
		add_meta_box(
			'wpas_author_details',
			__( 'Author Details', 'wp-author-showcase' ),
			[ $this, 'render_legacy_meta_box' ],
			'author_profile',
			'normal',
			'high'
		);
	}

	/**
	 * Render the legacy meta box.
	 * Will be removed in future versions.
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function render_legacy_meta_box( WP_Post $post ): void {
		// Add nonce for security
		wp_nonce_field( 'wpas_author_details', 'wpas_author_details_nonce' );

		// Get current values
		$email       = get_post_meta( $post->ID, 'wpas_author_email', true );
		$description = get_post_meta( $post->ID, 'wpas_author_description', true );
		?>
		<div class="wpas-meta-field">
			<label for="wpas_author_email"><?php esc_html_e( 'Email Address', 'wp-author-showcase' ); ?>:</label>
			<input type="email" id="wpas_author_email" name="wpas_author_email" value="<?php echo esc_attr( $email ); ?>" class="widefat">
		</div>

		<div class="wpas-meta-field" style="margin-top: 15px;">
			<label for="wpas_author_description"><?php esc_html_e( 'Description', 'wp-author-showcase' ); ?>:</label>
			<?php
			wp_editor(
				$description,
				'wpas_author_description',
				[
					'media_buttons' => false,
					'textarea_name' => 'wpas_author_description',
					'textarea_rows' => 5,
					'teeny'         => true,
				]
			);
			?>
		</div>
		<?php
	}

	/**
	 * Save the legacy meta box data.
	 * Will be removed in future versions.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public function save_legacy_meta_boxes( int $post_id ): void {
		// Check if our nonce is set
		if ( ! isset( $_POST['wpas_author_details_nonce'] ) ) {
			return;
		}

		// Verify the nonce
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpas_author_details_nonce'] ) ), 'wpas_author_details' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions
		if ( isset( $_POST['post_type'] ) && 'author_profile' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update email
		if ( isset( $_POST['wpas_author_email'] ) ) {
			update_post_meta(
				$post_id,
				'wpas_author_email',
				sanitize_email( wp_unslash( $_POST['wpas_author_email'] ) )
			);
		}

		// Update description
		if ( isset( $_POST['wpas_author_description'] ) ) {
			update_post_meta(
				$post_id,
				'wpas_author_description',
				wp_kses_post( wp_unslash( $_POST['wpas_author_description'] ) )
			);
		}
	}

	/**
	 * Get post type name.
	 *
	 * @return string The post type name.
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}
}
