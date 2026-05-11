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

	entry: async () => ( {
		...( await defaultConfig.entry() ),
		'admin/index': './src/admin/index.tsx',
	} ),

	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			'@': path.resolve( process.cwd(), 'src/admin' ),
		},
	},
	performance: {
		hints: false,
		maxEntrypointSize: 512000,
		maxAssetSize: 512000,
	},
	stats: {
		...defaultConfig.stats,
		reasons: true,
		source: true,
		errorDetails: true,
		logging: 'error',
	},
};

module.exports = updatedConfig;
