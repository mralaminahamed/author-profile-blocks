<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Profile Widget class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classic widget that renders a single author profile via the [apbl_profile] shortcode.
 */
class AuthorProfileWidget extends \WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'apbl_author_profile',
			__( 'Author Profile', 'author-profile-blocks' ),
			array(
				'description' => __( 'Display a single author profile card.', 'author-profile-blocks' ),
				'classname'   => 'apbl-author-profile-widget',
			)
		);
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * @param array<string,mixed> $args     Widget display arguments.
	 * @param array<string,mixed> $instance Saved widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ): void {
		echo wp_kses_post( $args['before_widget'] );

		$author_id = ! empty( $instance['author_id'] ) ? (int) $instance['author_id'] : 0;
		$style     = ! empty( $instance['style'] ) ? sanitize_html_class( $instance['style'] ) : 'card';
		$socials   = ! empty( $instance['show_socials'] ) ? 'true' : 'false';
		$bio       = ! empty( $instance['show_bio'] ) ? 'true' : 'false';

		if ( $author_id > 0 ) {
			echo do_shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				sprintf(
					'[apbl_profile id="%d" style="%s" show_socials="%s" show_bio="%s"]',
					$author_id,
					$style,
					$socials,
					$bio
				)
			);
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Render the widget admin form.
	 *
	 * @param array<string,mixed> $instance Saved widget settings.
	 * @return void
	 */
	public function form( $instance ): void {
		$author_id    = ! empty( $instance['author_id'] ) ? (int) $instance['author_id'] : 0;
		$style        = ! empty( $instance['style'] ) ? $instance['style'] : 'card';
		$show_socials = ! empty( $instance['show_socials'] );
		$show_bio     = isset( $instance['show_bio'] ) ? (bool) $instance['show_bio'] : true;

		$users = get_users( array( 'fields' => array( 'ID', 'display_name' ), 'number' => 100 ) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'author_id' ) ); ?>">
				<?php esc_html_e( 'Author:', 'author-profile-blocks' ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'author_id' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'author_id' ) ); ?>" class="widefat">
				<option value="0"><?php esc_html_e( '— Select Author —', 'author-profile-blocks' ); ?></option>
				<?php foreach ( $users as $user ) : ?>
					<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $author_id, $user->ID ); ?>>
						<?php echo esc_html( $user->display_name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
				<?php esc_html_e( 'Style:', 'author-profile-blocks' ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat">
				<?php foreach ( array( 'card', 'minimal', 'bordered', 'shadow' ) as $opt ) : ?>
					<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $style, $opt ); ?>>
						<?php echo esc_html( ucfirst( $opt ) ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_socials' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'show_socials' ) ); ?>"
				   <?php checked( $show_socials ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_socials' ) ); ?>">
				<?php esc_html_e( 'Show social links', 'author-profile-blocks' ); ?>
			</label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_bio' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'show_bio' ) ); ?>"
				   <?php checked( $show_bio ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_bio' ) ); ?>">
				<?php esc_html_e( 'Show bio', 'author-profile-blocks' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Sanitize widget settings on save.
	 *
	 * @param array<string,mixed> $new_instance New settings.
	 * @param array<string,mixed> $old_instance Old settings.
	 * @return array<string,mixed>
	 */
	public function update( $new_instance, $old_instance ): array {
		return array(
			'author_id'    => (int) ( $new_instance['author_id'] ?? 0 ),
			'style'        => sanitize_html_class( $new_instance['style'] ?? 'card' ),
			'show_socials' => ! empty( $new_instance['show_socials'] ),
			'show_bio'     => ! empty( $new_instance['show_bio'] ),
		);
	}
}
