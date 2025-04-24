const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry,

		// Individual block entries
		'blocks/author-profile/index': './src/blocks/author-profile/index.js',
		'blocks/author-grid/index': './src/blocks/author-grid/index.js',
		'blocks/author-carousel/index': './src/blocks/author-carousel/index.js',
		'blocks/author-carousel/view': './src/blocks/author-carousel/view.js',
		'blocks/author-list/index': './src/blocks/author-list/index.js',
	},

	// Ensure output paths match our structure
	output: {
		...defaultConfig.output,
		path: path.resolve(process.cwd(), 'build'),
		filename: '[name].js',
	},

	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			// Add aliases for easier imports if needed
			'@scss': path.resolve(process.cwd(), 'src/scss'),
			'@blocks': path.resolve(process.cwd(), 'src/blocks'),
			'@common': path.resolve(process.cwd(), 'src/scss/common'),
		}
	}
};
