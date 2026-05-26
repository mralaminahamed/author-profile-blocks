<?php
declare(strict_types=1);
 // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Data Provider class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Services;

use AuthorProfileBlocks\Core\Registerable;
use AuthorProfileBlocks\Core\UserMetaProvider;
use AuthorProfileBlocks\PostTypes\TeamMemberPostType;
use WP_User;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalizes WP Users and apbl_team_member posts into a shared author shape.
 */
class AuthorDataProvider implements Registerable {

	/**
	 * User meta provider instance.
	 *
	 * @var UserMetaProvider
	 */
	private UserMetaProvider $meta_provider;

	/**
	 * In-memory cache of normalized author data.
	 *
	 * @var array<string, array<string,mixed>>
	 */
	private array $cache = array();

	/**
	 * Constructor.
	 *
	 * @param UserMetaProvider $meta_provider The user meta provider.
	 */
	public function __construct( UserMetaProvider $meta_provider ) {
		$this->meta_provider = $meta_provider;
	}

	/**
	 * Register the provider.
	 *
	 * @return void
	 */
	public function register(): void {
		$this->init();
	}

	/**
	 * Initialize cache-busting hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'profile_update', array( $this, 'clear_cache' ) );
		add_action( 'save_post_' . TeamMemberPostType::POST_TYPE, array( $this, 'clear_cache' ) );
	}

	/**
	 * Get a single normalized author by ID and source.
	 *
	 * @param int    $id     WP user ID or team member post ID.
	 * @param string $source 'user' or 'team_member'.
	 * @return array<string,mixed>|null
	 */
	public function get_author( int $id, string $source = 'user' ): ?array {
		$cache_key = "{$source}_{$id}";
		if ( isset( $this->cache[ $cache_key ] ) ) {
			return $this->cache[ $cache_key ];
		}

		if ( 'team_member' === $source ) {
			$post = get_post( $id );
			if ( ! $post || TeamMemberPostType::POST_TYPE !== $post->post_type ) {
				return null;
			}
			$result = $this->normalize_team_member( $post );
		} else {
			$user = get_userdata( $id );
			if ( ! $user ) {
				return null;
			}
			$result = $this->normalize_user( $user );
		}

		$this->cache[ $cache_key ] = $result;
		return $result;
	}

	/**
	 * Get multiple normalized authors.
	 *
	 * @param array<string,mixed> $args {
	 *   @type string   $source     'user' or 'team_member'. Default 'user'.
	 *   @type int[]    $include    IDs to include (optional).
	 *   @type string   $role       WP role slug to filter users (optional).
	 *   @type string   $department Department term slug (optional).
	 *   @type int      $number     Max results. Default 10.
	 *   @type string   $orderby    Order field. Default 'display_name'.
	 * }
	 * @return array<int, array<string,mixed>>
	 */
	public function get_authors( array $args = array() ): array {
		$defaults = array(
			'source'     => 'user',
			'include'    => array(),
			'role'       => '',
			'department' => '',
			'number'     => 10,
			'orderby'    => 'display_name',
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( 'team_member' === $args['source'] ) {
			return $this->get_team_members( $args );
		}

		return $this->get_wp_users( $args );
	}

	/**
	 * Clear the in-memory cache.
	 *
	 * @return void
	 */
	public function clear_cache(): void {
		$this->cache = array();
	}

	/**
	 * Query WP users and normalize results.
	 *
	 * @param array<string,mixed> $args Query arguments.
	 * @return array<int, array<string,mixed>>
	 */
	private function get_wp_users( array $args ): array {
		$query_args = array(
			'number'  => $args['number'],
			'orderby' => $args['orderby'],
			'order'   => 'ASC',
			'fields'  => 'all',
		);
		if ( ! empty( $args['include'] ) ) {
			$query_args['include'] = $args['include'];
		}
		if ( ! empty( $args['role'] ) ) {
			$query_args['role'] = $args['role'];
		}

		$users = get_users( $query_args );
		return array_values( array_filter( array_map( array( $this, 'normalize_user' ), $users ) ) );
	}

	/**
	 * Query team member posts and normalize results.
	 *
	 * @param array<string,mixed> $args Query arguments.
	 * @return array<int, array<string,mixed>>
	 */
	private function get_team_members( array $args ): array {
		$query_args = array(
			'post_type'      => TeamMemberPostType::POST_TYPE,
			'posts_per_page' => $args['number'],
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);
		if ( ! empty( $args['include'] ) ) {
			$query_args['post__in'] = $args['include'];
		}
		if ( ! empty( $args['department'] ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'apbl_department',
					'field'    => 'slug',
					'terms'    => $args['department'],
				),
			);
		}

		$posts = get_posts( $query_args );
		return array_values( array_filter( array_map( array( $this, 'normalize_team_member' ), $posts ) ) );
	}

	/**
	 * Normalize a WP_User into the shared author shape.
	 *
	 * @param WP_User $user The WP user object.
	 * @return array<string,mixed>
	 */
	private function normalize_user( WP_User $user ): array {
		$socials = $this->meta_provider->get_meta( $user->ID, 'apbl_social_profiles', true );
		$skills  = $this->meta_provider->get_meta( $user->ID, 'apbl_skills', true );

		return array(
			'id'            => $user->ID,
			'name'          => $user->display_name,
			'url'           => get_author_posts_url( $user->ID ),
			'position'      => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_author_position', true ),
			'bio'           => $user->description,
			'avatar_url'    => get_avatar_url( $user->ID, array( 'size' => 150 ) ),
			'socials'       => is_array( $socials ) ? $socials : array(),
			'department'    => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_department', true ),
			'skills'        => ! empty( $skills ) ? array_map( 'trim', explode( ',', (string) $skills ) ) : array(),
			'location'      => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_location', true ),
			'phone'         => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_phone', true ),
			'availability'  => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_availability', true ),
			'website_label' => (string) $this->meta_provider->get_meta( $user->ID, 'apbl_website_label', true ),
			'source'        => 'user',
			'post_count'    => (int) count_user_posts( $user->ID ),
			'joined'        => $user->user_registered,
		);
	}

	/**
	 * Normalize a WP_Post (team member) into the shared author shape.
	 *
	 * @param WP_Post $post The team member post.
	 * @return array<string,mixed>
	 */
	private function normalize_team_member( WP_Post $post ): array {
		$socials_raw = get_post_meta( $post->ID, 'apbl_tm_social_profiles', true );
		$socials     = ! empty( $socials_raw ) ? json_decode( $socials_raw, true ) : array();
		$terms       = get_the_terms( $post->ID, 'apbl_department' );
		$department  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';

		return array(
			'id'            => $post->ID,
			'name'          => $post->post_title,
			'url'           => (string) get_permalink( $post ),
			'position'      => (string) get_post_meta( $post->ID, 'apbl_tm_position', true ),
			'bio'           => wp_strip_all_tags( $post->post_content ),
			'avatar_url'    => get_the_post_thumbnail_url( $post->ID, 'thumbnail' ) ?: '',
			'socials'       => is_array( $socials ) ? $socials : array(),
			'department'    => $department,
			'skills'        => array(),
			'location'      => '',
			'phone'         => '',
			'availability'  => '',
			'website_label' => '',
			'source'        => 'team_member',
			'post_count'    => 0,
			'joined'        => $post->post_date,
		);
	}
}
