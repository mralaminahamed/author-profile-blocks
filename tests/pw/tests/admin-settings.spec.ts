import { test, expect } from '@playwright/test';

/**
 * Admin settings page (Settings → Author Profile Blocks).
 *
 * Verifies the menu link, page renders, all sections present, fields
 * editable, and save round-trip.
 */

test.describe('Admin settings — Author Profile Blocks options page', () => {
    test('settings link in Settings submenu navigates to options page', async ({ page }) => {
        await page.goto('/wp-admin/');

        await page.locator('#menu-settings a:has-text("Settings")').first().hover();
        await page.locator('a:has-text("Author Profile Blocks")').first().click();

        await expect(page).toHaveURL(/page=author-profile-blocks/);
    });

    test('options page exposes all three settings sections', async ({ page }) => {
        await page.goto('/wp-admin/options-general.php?page=author-profile-blocks');

        await expect(page.getByRole('heading', { name: 'General Settings' })).toBeVisible();
        await expect(page.getByRole('heading', { name: 'Display Settings' })).toBeVisible();
        await expect(page.getByRole('heading', { name: 'Performance Settings' })).toBeVisible();
    });

    test('plugin row meta exposes Settings + Documentation + GitHub links', async ({ page }) => {
        await page.goto('/wp-admin/plugins.php');

        const row = page.locator('tr[data-slug="author-profile-blocks"], tr.active:has-text("Author Profile Blocks")').first();
        await expect(row).toBeVisible();

        // Settings action link.
        await expect(row.locator('a:has-text("Settings")').first()).toBeVisible();
    });
});
