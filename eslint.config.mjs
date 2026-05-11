import wordpressPlugin from '@wordpress/eslint-plugin';
import globals from 'globals';

export default [
	// Global ignores (replaces .eslintignore)
	{
		ignores: [
			'node_modules/**',
			'build/**',
			'vendor/**',
			'tests/**',
			'bin/**',
		],
	},

	// WordPress recommended flat config:
	// includes jsx-a11y, custom, react, esnext, i18n, import, prettier (auto), typescript (auto)
	...wordpressPlugin.configs.recommended,

	// Project-level overrides
	{
		languageOptions: {
			globals: {
				...globals.browser,
				wp: 'readonly',
				wpApiSettings: 'readonly',
			},
		},
		settings: {
			'import/resolver': {
				typescript: {
					project: './tsconfig.json',
				},
			},
		},
		rules: {
			indent: [ 'error', 'tab' ],
			'linebreak-style': [ 'error', 'unix' ],
			quotes: [ 'error', 'single' ],
			semi: [ 'error', 'always' ],
			'no-console': 'warn',
			'prettier/prettier': 'off',
		},
	},
];
