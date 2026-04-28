/**
 * External dependencies
 */
const path = require( 'path' );

/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const updatedConfig = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry,
		'admin/index': './src/admin/index.tsx',
	},

	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			'@': path.resolve( process.cwd(), 'src' ),
		},
	},
};

module.exports = updatedConfig;
