import { defineConfig, devices, expect } from '@playwright/test';
import { parseBoolean } from './utils/helpers';
import { customExpect } from './utils/pwMatchers';
import 'dotenv/config';

const { CI, HEADLESS, BASE_URL, SLOWMO } = process.env;

export default defineConfig({
    testDir: 'tests',
    outputDir: 'test-artifacts/',
    timeout: parseBoolean(CI) ? 60_000 : 90_000,
    expect: {
        timeout: 15_000,
    },
    fullyParallel: false,
    retries: parseBoolean(CI) ? 1 : 0,
    workers: 1,
    reporter: [
        ['list', { printSteps: true }],
        ['html', { open: 'never', outputFolder: 'test-results/html-report' }],
    ],

    use: {
        ...devices['Desktop Chrome'],
        acceptDownloads: true,
        actionTimeout: 15_000,
        navigationTimeout: 30_000,
        baseURL: BASE_URL ?? 'https://wc-affiliate.test',
        bypassCSP: true,
        headless: parseBoolean(HEADLESS ?? 'true'),
        ignoreHTTPSErrors: true,
        screenshot: { mode: 'only-on-failure', fullPage: true },
        trace: 'retain-on-failure',
        video: 'retain-on-failure',
        launchOptions: {
            slowMo: (parseInt(SLOWMO ?? '0') || 0) * 1000,
        },
    },

    projects: [
        {
            name: 'auth_setup',
            testMatch: /auth\.setup\.ts/,
        },
        {
            name: 'e2e',
            testMatch: /.*\.spec\.ts/,
            dependencies: ['auth_setup'],
            use: { storageState: '.auth/admin.json' },
        },
    ],
});

expect.extend(customExpect);
