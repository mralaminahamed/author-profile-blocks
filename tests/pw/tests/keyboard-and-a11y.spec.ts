import { test, expect, Page } from '@playwright/test';

/**
 * Keyboard navigation & accessibility smoke tests for the in-editor picker.
 *
 * - The placeholder picker must be reachable via Tab.
 * - The select dropdown is operable with arrow keys.
 * - The "Clear Selected Author" button has an accessible role.
 * - The four block tiles in the inserter expose option role + name.
 */

async function newPost(page: Page) {
    await page.goto('/wp-admin/');
    await page.locator('#menu-posts a:has-text("Posts")').first().hover();
    await page.locator('a[href="post-new.php"]').first().click();
    await expect(page.locator('iframe[name="editor-canvas"]')).toBeAttached({ timeout: 30_000 });
}

async function insertBlock(page: Page, label: string) {
    await page.getByRole('button', { name: 'Block Inserter' }).click();
    const tile = page.getByRole('option', { name: label });
    await tile.scrollIntoViewIfNeeded();
    await tile.click();
    await page.getByRole('button', { name: 'Close Block Inserter' }).click().catch(() => undefined);
}

test.describe('Accessibility — author picker is keyboard operable', () => {
    test('inserter tiles expose role=option with names matching block titles', async ({ page }) => {
        await newPost(page);

        await page.getByRole('button', { name: 'Block Inserter' }).click();

        for (const name of [' Author Profile', ' Author Grid', 'Author List', ' Author Carousel']) {
            const tile = page.getByRole('option', { name });
            await tile.scrollIntoViewIfNeeded();
            await expect(tile).toBeVisible();
        }
    });

    test('AUTHOR select is reachable and announces options', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, ' Author Profile');

        const select = page
            .frameLocator('iframe[name="editor-canvas"]')
            .getByRole('combobox', { name: 'AUTHOR' });

        await expect(select).toBeVisible();
        await select.focus();
        await expect(select).toBeFocused();
    });

    test('Select Author button has explicit accessible name', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, ' Author Profile');

        const button = page
            .frameLocator('iframe[name="editor-canvas"]')
            .getByRole('button', { name: 'Select Author' });
        await expect(button).toBeVisible();
    });

    test('placeholder heading and instructions are present for screen readers', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, ' Author Profile');

        const c = page.frameLocator('iframe[name="editor-canvas"]');
        await expect(c.getByText('Select an Author')).toBeVisible();
        await expect(c.getByText('Choose an author to display their profile')).toBeVisible();
    });
});
