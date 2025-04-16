const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry,

		// Individual block entries
		'blocks/author-profile/index': './src/blocks/author-profile/index.js',

		// Admin styles entry
		'admin/style': './src/scss/admin/style.scss',
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
