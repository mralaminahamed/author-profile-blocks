<?php
/**
 * Author Profile custom post type class
 *
 * @package WPAuthorShowcase
 * @subpackage PostTypes
 */

namespace WPAuthorShowcase\Post_Types;

use WP_Post;
use WP_Query;
use function __;
use function _x;
use function add_action;
use function add_filter;
use function add_meta_box;
use function current_user_can;
use function esc_attr;
use function esc_html;
use function esc_html_e;
use function get_post_meta;
use function printf;
use function register_post_meta;
use function register_post_type;
use function sanitize_email;
use function sanitize_text_field;
use function strlen;
use function substr;
use function update_post_meta;
use function wp_editor;
use function wp_kses_post;
use function wp_nonce_field;
use function wp_strip_all_tags;
use function wp_unslash;
use function wp_verify_nonce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Profile custom post type.
 */
class Author_Profile_CPT {
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
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_meta_fields' ) );

		// Legacy code support - will be removed in future versions.
		add_action( 'add_meta_boxes', array( $this, 'register_legacy_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_legacy_meta_boxes' ) );

		// Add custom columns to the admin list table, render them, and make them sortable.
		add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
		add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', array( $this, 'make_custom_columns_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'sort_custom_columns' ) );

		// Add custom styles to the admin area.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		$labels = array(
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
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'author-profile' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-id',
			'supports'           => array( 'title', 'thumbnail', 'custom-fields' ),
			'show_in_rest'       => true,
		);

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
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_email',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		// Register description field.
		register_post_meta(
			$this->post_type,
			'wpas_author_description',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
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
			array( $this, 'render_legacy_meta_box' ),
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
				array(
					'media_buttons' => false,
					'textarea_name' => 'wpas_author_description',
					'textarea_rows' => 5,
					'teeny'         => true,
				)
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
	 * Add custom columns to the admin list table.
	 *
	 * @param array $columns The existing columns.
	 * @return array The modified columns.
	 */
	public function add_custom_columns( array $columns ): array {
		$new_columns = array();

		// Insert columns after checkbox, but before date
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			if ( 'cb' === $key ) {
				$new_columns['author_image']       = __( 'Image', 'wp-author-showcase' );
				$new_columns['title']              = __( 'Name', 'wp-author-showcase' );
				$new_columns['author_email']       = __( 'Email', 'wp-author-showcase' );
				$new_columns['author_description'] = __( 'Description', 'wp-author-showcase' );
			}
		}

		return $new_columns;
	}

	/**
	 * Render the custom columns in the admin list table.
	 *
	 * @param string $column   The column name.
	 * @param int    $post_id  The post ID.
	 * @return void
	 */
	public function render_custom_columns( string $column, int $post_id ): void {
		switch ( $column ) {
			case 'author_image':
				$image_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
				if ( ! empty( $image_url ) ) {
					printf(
						'<div class="author-image-wrapper"><img src="%s" alt="" class="author-thumbnail" /></div>',
						esc_url( $image_url )
					);
				} else {
					echo '<span class="no-image">—</span>';
				}
				break;

			case 'author_email':
				$email = get_post_meta( $post_id, 'wpas_author_email', true );
				if ( ! empty( $email ) ) {
					printf(
						'<div class="author-email-wrapper"><a href="mailto:%1$s">%2$s</a></div>',
						esc_attr( $email ),
						esc_html( $email )
					);
				} else {
					echo '<span class="no-email">—</span>';
				}
				break;

			case 'author_description':
				$description = get_post_meta( $post_id, 'wpas_author_description', true );
				if ( ! empty( $description ) ) {
					// Truncate description to 150 characters
					$truncated       = wp_strip_all_tags( $description );
					$original_length = strlen( $truncated );
					$is_truncated    = false;

					if ( $original_length > 150 ) {
						$truncated = substr( $truncated, 0, 150 );
						// Ensure we don't cut off in the middle of a word
						if ( $truncated !== $description ) {
							// Find the last space within the truncated string
							$last_space = strrpos( $truncated, ' ' );
							if ( $last_space !== false ) {
								$truncated = substr( $truncated, 0, $last_space );
							}
							$truncated   .= '...';
							$is_truncated = true;
						}
					}

					$description_meta = '';
					if ( $is_truncated && $original_length > 0 ) {
						$description_meta = sprintf(
							'<div class="description-meta"><span class="description-count">%s</span></div>',
							sprintf(
							/* translators: %d: number of characters */
								esc_html__( '%d characters', 'wp-author-showcase' ),
								$original_length
							)
						);
					}

					printf(
						'<div class="author-description-wrapper"><span class="description-text">%s</span>%s</div>',
						esc_html( $truncated ),
						wp_kses_post( $description_meta )
					);
				} else {
					echo '<span class="no-description">—</span>';
				}
				break;
		}
	}

	/**
	 * Make custom columns sortable.
	 *
	 * @param array $columns The sortable columns.
	 * @return array The modified sortable columns.
	 */
	public function make_custom_columns_sortable( array $columns ): array {
		$columns['author_email'] = 'author_email';
		return $columns;
	}

	/**
	 * Add sorting functionality for custom columns.
	 *
	 * @param WP_Query $query The WordPress query object.
	 *
	 * @return void
	 */
	public function sort_custom_columns( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'author_email' === $orderby ) {
			$query->set( 'meta_key', 'wpas_author_email' );
			$query->set( 'orderby', 'meta_value' );
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

	/**
	 * Add admin styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		global $post_type;

		// Only add styles on the author profile list page
		if ( $this->post_type !== $post_type ) {
			return;
		}

		wp_enqueue_style(
			'wpas-admin-styles',
			WPAS_PLUGIN_URL . 'build/admin/styles.css',
			array(),
			WPAS_VERSION
		);
	}
}
