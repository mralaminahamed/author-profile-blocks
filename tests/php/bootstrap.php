<?php

define( 'TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR', dirname( __DIR__, 2 ) );

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/vendor/autoload.php';

// Check if this is a unit test that doesn't require WordPress
if ( getenv( 'UNIT_TEST' ) === 'true' ) {
	// For unit tests, just load the autoloader and exit
	return;
}

$_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : getenv( 'WP_PHPUNIT__DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	require TEST_AUTHOR_PROFILE_BLOCKS_PLUGIN_DIR . '/author-profile-blocks.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
