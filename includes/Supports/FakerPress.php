<?php
/**
 * FakerPress Support for Author Profile Blocks
 *
 * @package AuthorProfileBlocks
 */

namespace AuthorProfileBlocks\Supports;

use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FakerPress integration support class
 */
class FakerPress {

	/**
	 * Initialize FakerPress integration.
	 *
	 * Registers custom meta fields with FakerPress for automatic user generation.
	 *
	 * @return void
	 */
	public static function init(): void {
		// Only proceed if FakerPress is active.
		if ( ! class_exists( 'FakerPress\Plugin' ) ) {
			return;
		}

		// Hook into FakerPress to register our custom meta types.
		add_filter( 'fakerpress/fields/meta_types', array( self::class, 'register_meta_types' ) );

		// Hook into user generation to set default values for our meta fields.
		add_action( 'fakerpress.module.user.before_save', array( self::class, 'set_user_defaults' ), 10, 2 );

		// Hook into specific meta key filters for more targeted generation.
		add_filter( 'fakerpress.module.meta.apbl_author_description.value', array( self::class, 'generate_author_description_value' ), 10, 3 );
		add_filter( 'fakerpress.module.meta.apbl_author_position.value', array( self::class, 'generate_author_position_value' ), 10, 3 );
		add_filter( 'fakerpress.module.meta.apbl_social_profiles.value', array( self::class, 'generate_social_profiles_value' ), 10, 3 );
		add_filter( 'fakerpress.module.meta.apbl_member_since_label.value', array( self::class, 'generate_member_since_label_value' ), 10, 3 );
	}

	/**
	 * Register custom meta types with FakerPress.
	 *
	 * @param mixed $meta_types Existing meta types (array or stdClass).
	 *
	 * @return mixed Modified meta types.
	 */
	public static function register_meta_types( $meta_types ) {
		$meta_types->apbl_author_description = (object) array(
			'value'       => '',
			'text'        => __( 'Author Description', 'author-profile-blocks' ),
			'description' => __( 'A detailed description for the author profile.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_author_position    = (object) array(
			'value'       => '',
			'text'        => __( 'Author Position/Title', 'author-profile-blocks' ),
			'description' => __( 'The author\'s position or job title.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_social_profiles    = (object) array(
			'value'       => '',
			'text'        => __( 'Social Media Profiles', 'author-profile-blocks' ),
			'description' => __( 'Social media profile URLs for the author.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);
		$meta_types->apbl_member_since_label = (object) array(
			'value'       => '',
			'text'        => __( 'Member Since Label', 'author-profile-blocks' ),
			'description' => __( 'Custom label for the member since date.', 'author-profile-blocks' ),
			'category'    => 'author-profile-blocks',
			'group'       => 'Author Profile Blocks',
		);

		return $meta_types;
	}

	/**
	 * Set default values for Author Profile Blocks meta fields during FakerPress user generation.
	 *
	 * @param WP_User $user     The user object being generated.
	 * @param array   $_user_data The user data array.
	 *
	 * @return void
	 */
	public static function set_user_defaults( WP_User $user, array $_user_data ): void {
		// Set default member since label if not already set.
		if ( ! metadata_exists( 'user', $user->ID, 'apbl_member_since_label' ) ) {
			update_user_meta( $user->ID, 'apbl_member_since_label', __( 'Member since', 'author-profile-blocks' ) );
		}

		// Initialize empty social profiles array if not already set.
		if ( ! metadata_exists( 'user', $user->ID, 'apbl_social_profiles' ) ) {
			update_user_meta(
				$user->ID,
				'apbl_social_profiles',
				array(
					'facebook'  => '',
					'twitter'   => '',
					'linkedin'  => '',
					'instagram' => '',
					'website'   => '',
				)
			);
		}
	}

	/**
	 * Generate value for apbl_author_description meta key.
	 *
	 * @param mixed  $value  The current meta value.
	 * @param string $_type  The field type.
	 * @param object $_module The FakerPress module object.
	 *
	 * @return string The generated author description.
	 */
	public static function generate_author_description_value( $value, string $_type, object $_module ): string {
		if ( empty( $value ) ) {
			return self::generate_author_description();
		}
		return $value;
	}

	/**
	 * Generate value for apbl_author_position meta key.
	 *
	 * @param mixed  $value  The current meta value.
	 * @param string $_type  The field type.
	 * @param object $_module The FakerPress module object.
	 *
	 * @return string The generated author position.
	 */
	public static function generate_author_position_value( $value, string $_type, object $_module ): string {
		if ( empty( $value ) ) {
			return self::generate_author_position();
		}
		return $value;
	}

	/**
	 * Generate value for apbl_social_profiles meta key.
	 *
	 * @param mixed  $value  The current meta value.
	 * @param string $_type  The field type.
	 * @param object $_module The FakerPress module object.
	 *
	 * @return array The generated social profiles.
	 */
	public static function generate_social_profiles_value( $value, string $_type, object $_module ): array {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return self::generate_social_profiles();
		}
		return $value;
	}

	/**
	 * Generate value for apbl_member_since_label meta key.
	 *
	 * @param mixed  $value  The current meta value.
	 * @param string $_type  The field type.
	 * @param object $_module The FakerPress module object.
	 *
	 * @return string The generated member since label.
	 */
	public static function generate_member_since_label_value( $value, string $_type, object $_module ): string {
		if ( empty( $value ) ) {
			return self::generate_member_since_label();
		}
		return $value;
	}

	/**
	 * Generate a realistic author description.
	 *
	 * @return string The generated author description.
	 */
	private static function generate_author_description(): string {
		$descriptions = array(
			__( 'Passionate content creator with over 5 years of experience in digital marketing and creative writing. Specializes in crafting compelling narratives that engage audiences and drive results.', 'author-profile-blocks' ),
			__( 'Experienced developer and tech enthusiast who loves building innovative solutions. Always staying up-to-date with the latest technologies and sharing knowledge with the community.', 'author-profile-blocks' ),
			__( 'Creative designer with an eye for detail and a passion for user experience. Combines artistic vision with technical expertise to create beautiful, functional designs.', 'author-profile-blocks' ),
			__( 'Dedicated educator and lifelong learner committed to sharing knowledge and helping others grow. Believes in the power of education to transform lives and communities.', 'author-profile-blocks' ),
			__( 'Strategic thinker and business professional with a track record of driving growth and innovation. Passionate about helping organizations achieve their goals through smart strategies.', 'author-profile-blocks' ),
		);

		return $descriptions[ array_rand( $descriptions ) ];
	}

	/**
	 * Generate a realistic author position/title.
	 *
	 * @return string The generated author position.
	 */
	private static function generate_author_position(): string {
		$positions = array(
			__( 'Senior Content Writer', 'author-profile-blocks' ),
			__( 'Lead Developer', 'author-profile-blocks' ),
			__( 'Creative Director', 'author-profile-blocks' ),
			__( 'Marketing Manager', 'author-profile-blocks' ),
			__( 'Product Manager', 'author-profile-blocks' ),
			__( 'UX Designer', 'author-profile-blocks' ),
			__( 'Technical Writer', 'author-profile-blocks' ),
			__( 'Community Manager', 'author-profile-blocks' ),
			__( 'Business Analyst', 'author-profile-blocks' ),
			__( 'Project Coordinator', 'author-profile-blocks' ),
		);

		return $positions[ array_rand( $positions ) ];
	}

	/**
	 * Generate realistic social media profiles.
	 *
	 * @return array The generated social profiles array.
	 */
	private static function generate_social_profiles(): array {
		$profiles = array(
			'facebook'  => '',
			'twitter'   => '',
			'linkedin'  => '',
			'instagram' => '',
			'website'   => '',
		);

		// Randomly populate some social profiles (70% chance for each).
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['facebook'] = 'https://facebook.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['twitter'] = 'https://twitter.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['linkedin'] = 'https://linkedin.com/in/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['instagram'] = 'https://instagram.com/example' . wp_rand( 1000, 9999 );
		}
		if ( wp_rand( 0, 9 ) < 7 ) {
			$profiles['website'] = 'https://example' . wp_rand( 1000, 9999 ) . '.com';
		}

		return $profiles;
	}

	/**
	 * Generate a member since label.
	 *
	 * @return string The generated member since label.
	 */
	private static function generate_member_since_label(): string {
		$labels = array(
			__( 'Member since', 'author-profile-blocks' ),
			__( 'Joined', 'author-profile-blocks' ),
			__( 'With us since', 'author-profile-blocks' ),
			__( 'Active since', 'author-profile-blocks' ),
		);

		return $labels[ array_rand( $labels ) ];
	}
}
