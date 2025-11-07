<?php

namespace AuthorProfileBlocks\Test\CustomAssertion;

/**
 * Custom assertions for nested array structures.
 */
trait NestedArrayAssertionTrait {

    /**
     * Assert that an array has a nested key with the expected value.
     *
     * @param array  $array The array to check.
     * @param array  $keys The nested keys path (e.g., ['key1', 'key2']).
     * @param mixed  $expected_value Expected value.
     * @param string $message Optional assertion message.
     */
    protected function assertNestedArrayKeyValue( array $array, array $keys, $expected_value, string $message = '' ): void {
        $current = $array;
        $path = '';

        foreach ( $keys as $key ) {
            $path .= "['{$key}']";
            $this->assertArrayHasKey( $key, $current, $message ?: "Array should have key {$path}" );
            $current = $current[ $key ];
        }

        $this->assertEquals( $expected_value, $current, $message ?: "Nested array value at {$path} should equal expected value" );
    }

    /**
     * Assert that an array has nested keys.
     *
     * @param array  $array The array to check.
     * @param array  $keys The nested keys path.
     * @param string $message Optional assertion message.
     */
    protected function assertNestedArrayHasKeys( array $array, array $keys, string $message = '' ): void {
        $current = $array;
        $path = '';

        foreach ( $keys as $key ) {
            $path .= "['{$key}']";
            $this->assertArrayHasKey( $key, $current, $message ?: "Array should have key {$path}" );
            $current = $current[ $key ];
        }
    }

    /**
     * Assert that nested array values match expected structure.
     *
     * @param array  $actual The actual array.
     * @param array  $expected The expected array structure.
     * @param string $message Optional assertion message.
     */
    protected function assertNestedArrayStructure( array $actual, array $expected, string $message = '' ): void {
        foreach ( $expected as $key => $value ) {
            $this->assertArrayHasKey( $key, $actual, $message ?: "Array should have key '{$key}'" );

            if ( is_array( $value ) ) {
                $this->assertIsArray( $actual[ $key ], $message ?: "Key '{$key}' should be an array" );
                $this->assertNestedArrayStructure( $actual[ $key ], $value, $message );
            } else {
                $this->assertEquals( $value, $actual[ $key ], $message ?: "Key '{$key}' should equal expected value" );
            }
        }
    }
}