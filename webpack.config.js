const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,

		// Author Showcase block entry
		'author-profile/index': './src/author-profile/index.js',

		// Admin styles entry
		'admin/styles': './src/admin/styles.scss',
	}
};
