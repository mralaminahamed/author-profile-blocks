import { Page, expect } from '@playwright/test';

/**
 * Shared helpers for the per-block specs. Centralised here so each spec
 * stays focused on the block under test.
 */

export async function newPost(page: Page): Promise<void> {
    await page.goto('/wp-admin/');
    await page.locator('#menu-posts a:has-text("Posts")').first().hover();
    await page.locator('a[href="post-new.php"]').first().click();
    await expect(
        page.locator('iframe[name="editor-canvas"]')
    ).toBeAttached({ timeout: 30_000 });
}

export async function insertBlock(page: Page, label: string): Promise<void> {
    await page.getByRole('button', { name: 'Block Inserter' }).click();
    const tile = page.getByRole('option', { name: label });
    await tile.scrollIntoViewIfNeeded();
    await tile.click();
    await page
        .getByRole('button', { name: 'Close Block Inserter' })
        .click()
        .catch(() => undefined);
}

export const canvas = (page: Page) =>
    page.frameLocator('iframe[name="editor-canvas"]');

export async function pickFirstAuthor(
    page: Page,
    addButton: 'Select Author' | 'Add Author'
): Promise<void> {
    const c       = canvas(page);
    const select  = c.getByRole('combobox', { name: 'AUTHOR' });
    const button  = c.getByRole('button', { name: addButton });

    await expect(select).toBeVisible();
    await expect.poll(
        async () => await select.locator('option').count(),
        { timeout: 15_000 }
    ).toBeGreaterThan(1);

    const value = await select.locator('option').nth(1).getAttribute('value');
    await select.selectOption(value!);
    await button.click();
}

export async function openInspectorTab(
    page: Page,
    name: 'Content' | 'Style' | 'Layout' | 'Advanced'
): Promise<void> {
    await page.getByRole('tab', { name }).click();
}
