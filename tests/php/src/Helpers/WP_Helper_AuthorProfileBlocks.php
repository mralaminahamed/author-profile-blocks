<?php

namespace AuthorProfileBlocks\Test\Helpers;

/**
 * Helper class for Author Profile Blocks testing.
 */
class WP_Helper_AuthorProfileBlocks {

    /**
     * Create a test user with author role and profile data.
     *
     * @param array $args User creation arguments.
     * @return int User ID.
     */
    public static function create_author( array $args = [] ): int {
        $defaults = [
            'user_login' => 'test_author_' . wp_generate_password( 8, false ),
            'user_email' => 'test_author_' . wp_generate_password( 8, false ) . '@example.com',
            'user_pass' => 'password',
            'role' => 'author',
            'first_name' => 'Test',
            'last_name' => 'Author',
            'description' => 'Test author bio',
        ];

        $args = array_merge( $defaults, $args );
        return wp_insert_user( $args );
    }

    /**
     * Create multiple test authors.
     *
     * @param int   $count Number of authors to create.
     * @param array $args Base arguments for all authors.
     * @return array Array of user IDs.
     */
    public static function create_authors( int $count, array $args = [] ): array {
        $user_ids = [];

        for ( $i = 0; $i < $count; $i++ ) {
            $author_args = $args;
            $author_args['user_login'] = isset( $args['user_login'] ) ? $args['user_login'] . '_' . $i : 'test_author_' . $i;
            $author_args['user_email'] = isset( $args['user_email'] ) ? str_replace( '@', '_' . $i . '@', $args['user_email'] ) : 'test_author_' . $i . '@example.com';

            $user_ids[] = self::create_author( $author_args );
        }

        return $user_ids;
    }

    /**
     * Add profile meta data to a user.
     *
     * @param int   $user_id User ID.
     * @param array $meta_data Meta data to add.
     */
    public static function add_author_meta( int $user_id, array $meta_data ): void {
        foreach ( $meta_data as $key => $value ) {
            update_user_meta( $user_id, $key, $value );
        }
    }

    /**
     * Create a test post authored by a specific user.
     *
     * @param int   $author_id Author user ID.
     * @param array $args Post arguments.
     * @return int Post ID.
     */
    public static function create_author_post( int $author_id, array $args = [] ): int {
        $defaults = [
            'post_title' => 'Test Post by Author ' . $author_id,
            'post_content' => 'Test post content',
            'post_status' => 'publish',
            'post_author' => $author_id,
            'post_type' => 'post',
        ];

        $args = array_merge( $defaults, $args );
        return wp_insert_post( $args );
    }

    /**
     * Create multiple posts for an author.
     *
     * @param int   $author_id Author user ID.
     * @param int   $count Number of posts to create.
     * @param array $args Base post arguments.
     * @return array Array of post IDs.
     */
    public static function create_author_posts( int $author_id, int $count, array $args = [] ): array {
        $post_ids = [];

        for ( $i = 0; $i < $count; $i++ ) {
            $post_args = $args;
            $post_args['post_title'] = isset( $args['post_title'] ) ? $args['post_title'] . ' ' . $i : 'Test Post ' . $i;

            $post_ids[] = self::create_author_post( $author_id, $post_args );
        }

        return $post_ids;
    }

    /**
     * Clean up test users and their data.
     *
     * @param array $user_ids Array of user IDs to clean up.
     */
    public static function cleanup_test_users( array $user_ids ): void {
        foreach ( $user_ids as $user_id ) {
            // Delete user's posts
            $posts = get_posts( [
                'author' => $user_id,
                'post_type' => 'any',
                'numberposts' => -1,
                'post_status' => 'any',
            ] );

            foreach ( $posts as $post ) {
                wp_delete_post( $post->ID, true );
            }

            // Delete user
            wp_delete_user( $user_id );
        }
    }

    /**
     * Get author profile data.
     *
     * @param int $user_id User ID.
     * @return array Author profile data.
     */
    public static function get_author_profile( int $user_id ): array {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return [];
        }

        return [
            'ID' => $user->ID,
            'display_name' => $user->display_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'description' => $user->description,
            'user_url' => $user->user_url,
            'avatar' => get_avatar_url( $user_id ),
            'posts_count' => count_user_posts( $user_id ),
            'meta' => [
                'job_title' => get_user_meta( $user_id, 'job_title', true ),
                'company' => get_user_meta( $user_id, 'company', true ),
                'location' => get_user_meta( $user_id, 'location', true ),
                'social_links' => get_user_meta( $user_id, 'social_links', true ),
            ],
        ];
    }
}