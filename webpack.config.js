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

	// entry: {
	// 	...defaultConfig.entry,
	//
	// 	// Individual block entries
	// 	'blocks/author-profile/index': './src/blocks/author-profile/index.js',
	// 	'blocks/author-grid/index': './src/blocks/author-grid/index.js',
	// 	'blocks/author-carousel/index': './src/blocks/author-carousel/index.js',
	// 	'blocks/author-carousel/view': './src/blocks/author-carousel/view.js',
	// 	'blocks/author-list/index': './src/blocks/author-list/index.js',
	// },

	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			// Path alias for src directory
			'@': path.resolve( process.cwd(), 'src' ),
		},
	},
};

module.exports = updatedConfig;
