import { test, expect, Page } from '@playwright/test';

/**
 * Clearing a selected author returns the block to its empty placeholder
 * state. This is the inverse path of selection — equally important.
 */

async function newPost(page: Page) {
    await page.goto('/wp-admin/');
    await page.locator('#menu-posts a:has-text("Posts")').first().hover();
    await page.locator('a[href="post-new.php"]').first().click();
    await expect(page.locator('iframe[name="editor-canvas"]')).toBeAttached({ timeout: 30_000 });
}

async function insertProfile(page: Page) {
    await page.getByRole('button', { name: 'Block Inserter' }).click();
    const tile = page.getByRole('option', { name: ' Author Profile' });
    await tile.scrollIntoViewIfNeeded();
    await tile.click();
    await page.getByRole('button', { name: 'Close Block Inserter' }).click().catch(() => undefined);
}

const canvas = (page: Page) => page.frameLocator('iframe[name="editor-canvas"]');

test('clearing a selected author returns the picker', async ({ page }) => {
    await newPost(page);
    await insertProfile(page);

    const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
    const select_btn = canvas(page).getByRole('button', { name: 'Select Author' });

    await expect(select).toBeVisible();
    await expect.poll(
        async () => await select.locator('option').count(),
        { timeout: 15_000 }
    ).toBeGreaterThan(1);

    const value = await select.locator('option').nth(1).getAttribute('value');
    await select.selectOption(value!);
    await select_btn.click();

    // Preview is now visible — Clear button appears in the inspector.
    const clearButton = page.getByRole('button', { name: 'Clear Selected Author' });
    await expect(clearButton).toBeVisible({ timeout: 15_000 });

    await clearButton.click();

    // After clearing, the placeholder picker is back.
    await expect(canvas(page).getByText('Select an Author')).toBeVisible();
    await expect(canvas(page).getByRole('combobox', { name: 'AUTHOR' })).toBeVisible();
});
