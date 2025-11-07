<?php

namespace AuthorProfileBlocks\Test\Factories;

// Base factory class for unit tests
class AuthorFactory {
    /**
     * Get user factory.
     */
    public function user() {
        return new class {
            public function create($args = []) {
                // Return a mock user ID for unit tests
                return rand(1000, 9999);
            }
        };
    }

    /**
     * Get author factory.
     */
    public function author() {
        return new class {
            public function create($args = []) {
                // Return a mock user ID for unit tests
                return rand(1000, 9999);
            }
        };
    }
}