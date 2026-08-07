import { defineConfig } from '@playwright/test';

/**
 * Config for regenerating the WordPress.org listing assets.
 *
 *   yarn shots:banners   — icon + banners (renders markup, no site needed)
 *   yarn shots:wporg     — screenshots (drives a logged-in WordPress admin)
 *
 * Deliberately separate from tests/pw/playwright.config.ts: that project is
 * the end-to-end suite, with its own environment setup and its own npm
 * workspace. Asset rendering shares none of that and should not be able to
 * break it, or be broken by it.
 *
 * Uses `channel: 'chrome'` so it drives an already-installed Chrome rather
 * than requiring `playwright install`, and pins the viewport to 1440x900 so
 * every screenshot in .wordpress-org/ comes out the same size.
 */
export default defineConfig( {
	testDir: './tests/assets',
	timeout: 90_000,
	fullyParallel: false,
	workers: 1,

	/*
	 * Retry twice.
	 *
	 * These drive a local dev site, and Chrome intermittently fails a burst of
	 * subresources. When a block's editor script lands in that burst the editor
	 * never finishes mounting and the capture photographs a half-drawn canvas —
	 * on a run that had just succeeded unchanged.
	 *
	 * Two retries clear the flake without hiding a real fault: a genuinely
	 * broken page fails all three attempts.
	 */
	retries: 2,
	reporter: [ [ 'list' ] ],
	use: {
		baseURL: process.env.WP_BASE_URL || 'http://wp-plugin-dev.test',
		ignoreHTTPSErrors: true,
		screenshot: 'off',
		video: 'off',
		trace: 'off',
	},
	projects: [
		{ name: 'setup', testMatch: 'auth.setup.ts', use: { channel: 'chrome' } },
		{
			name: 'banners',
			testMatch: 'wporg-brand.spec.ts',
			use: { channel: 'chrome' },
		},
		{
			name: 'shots',
			testMatch: 'wporg-shots.spec.ts',
			dependencies: [ 'setup' ],
			use: {
				channel: 'chrome',
				viewport: { width: 1440, height: 900 },
				storageState: 'tests/assets/.auth/admin.json',
			},
		},
	],
} );
