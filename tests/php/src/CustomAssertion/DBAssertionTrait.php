<?php

namespace AuthorProfileBlocks\Test\CustomAssertion;

/**
 * Custom assertions for database-related tests.
 */
trait DBAssertionTrait {

    /**
     * Assert that a post meta exists for the given post and key.
     *
     * @param int    $post_id Post ID.
     * @param string $meta_key Meta key.
     * @param mixed  $meta_value Expected meta value.
     * @param string $message Optional assertion message.
     */
    protected function assertPostMetaExists( int $post_id, string $meta_key, $meta_value = null, string $message = '' ): void {
        $actual_value = get_post_meta( $post_id, $meta_key, true );

        if ( $meta_value === null ) {
            $this->assertNotEmpty( $actual_value, $message ?: "Post meta '{$meta_key}' should exist for post {$post_id}" );
        } else {
            $this->assertEquals( $meta_value, $actual_value, $message ?: "Post meta '{$meta_key}' should equal expected value for post {$post_id}" );
        }
    }

    /**
     * Assert that a user meta exists for the given user and key.
     *
     * @param int    $user_id User ID.
     * @param string $meta_key Meta key.
     * @param mixed  $meta_value Expected meta value.
     * @param string $message Optional assertion message.
     */
    protected function assertUserMetaExists( int $user_id, string $meta_key, $meta_value = null, string $message = '' ): void {
        $actual_value = get_user_meta( $user_id, $meta_key, true );

        if ( $meta_value === null ) {
            $this->assertNotEmpty( $actual_value, $message ?: "User meta '{$meta_key}' should exist for user {$user_id}" );
        } else {
            $this->assertEquals( $meta_value, $actual_value, $message ?: "User meta '{$meta_key}' should equal expected value for user {$user_id}" );
        }
    }

    /**
     * Assert that an option exists with the expected value.
     *
     * @param string $option_name Option name.
     * @param mixed  $expected_value Expected option value.
     * @param string $message Optional assertion message.
     */
    protected function assertOptionExists( string $option_name, $expected_value = null, string $message = '' ): void {
        $actual_value = get_option( $option_name );

        if ( $expected_value === null ) {
            $this->assertNotFalse( $actual_value, $message ?: "Option '{$option_name}' should exist" );
        } else {
            $this->assertEquals( $expected_value, $actual_value, $message ?: "Option '{$option_name}' should equal expected value" );
        }
    }

    /**
     * Assert that a term exists in the given taxonomy.
     *
     * @param string $term_name Term name.
     * @param string $taxonomy Taxonomy name.
     * @param string $message Optional assertion message.
     */
    protected function assertTermExists( string $term_name, string $taxonomy, string $message = '' ): void {
        $term = get_term_by( 'name', $term_name, $taxonomy );
        $this->assertNotFalse( $term, $message ?: "Term '{$term_name}' should exist in taxonomy '{$taxonomy}'" );
    }
}