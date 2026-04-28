import { test as setup, expect, request } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

/**
 * Persist an authenticated admin session in `.auth/admin.json` and reuse it
 * on subsequent runs. The setup re-uses the saved state when:
 *   1. the file exists, AND
 *   2. its session cookie still authenticates against `/wp-admin/`.
 *
 * Otherwise log in fresh and overwrite the file.
 *
 * Override creds with WP_ADMIN_USER / WP_ADMIN_PASS env vars.
 * Force re-auth with FORCE_AUTH=1.
 */
setup('authenticate as admin (reuse if valid)', async ({ page, baseURL }) => {
    const username   = process.env.WP_ADMIN_USER ?? 'admin';
    const password   = process.env.WP_ADMIN_PASS ?? 'password';
    const force      = process.env.FORCE_AUTH === '1';
    const storageDir = path.resolve(__dirname, '..', '.auth');
    const storageFile = path.join(storageDir, 'admin.json');

    if (!fs.existsSync(storageDir)) {
        fs.mkdirSync(storageDir, { recursive: true });
    }

    if (!force && fs.existsSync(storageFile)) {
        // Validate saved state by hitting wp-admin with the saved cookies.
        const ctx = await request.newContext({
            baseURL,
            storageState: storageFile,
            ignoreHTTPSErrors: true,
        });
        const res     = await ctx.get('/wp-admin/');
        const html    = await res.text();
        const isLogin = html.includes('id="loginform"') || html.includes('name="log"');
        await ctx.dispose();

        if (res.ok() && !isLogin) {
            console.log('[auth] reusing saved storage state');
            return;
        }
        console.log('[auth] saved storage state expired, re-authenticating');
    }

    console.log('[auth] performing fresh login');
    await page.goto('/wp-login.php');
    await page.fill('#user_login', username);
    await page.fill('#user_pass', password);
    await page.click('#wp-submit');
    await expect(page.locator('#wpadminbar')).toBeVisible({ timeout: 30_000 });

    await page.context().storageState({ path: storageFile });
    console.log(`[auth] storage state saved to ${storageFile}`);
});
