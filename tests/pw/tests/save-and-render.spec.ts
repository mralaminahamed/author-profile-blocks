import { test, expect, Page } from '@playwright/test';

/**
 * End-to-end save + frontend render. Insert a block, pick an author, save
 * the post as a draft, and verify the editor preview reflects the choice.
 * Also verify the post survives a reload (selection persisted to attrs).
 */

async function newPostFromMenu(page: Page) {
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

async function setPostTitle(page: Page, title: string) {
    const titleInput = page
        .frameLocator('iframe[name="editor-canvas"]')
        .getByRole('textbox', { name: 'Add title' });
    await titleInput.click();
    await page.keyboard.type(title);
}

async function pickFirstAuthor(page: Page, addButton: string) {
    const c      = page.frameLocator('iframe[name="editor-canvas"]');
    const select = c.getByRole('combobox', { name: 'AUTHOR' });
    const button = c.getByRole('button', { name: addButton });

    await expect(select).toBeVisible();
    await expect.poll(
        async () => await select.locator('option').count(),
        { timeout: 15_000 }
    ).toBeGreaterThan(1);

    const value = await select.locator('option').nth(1).getAttribute('value');
    await select.selectOption(value!);
    await button.click();
}

async function saveDraft(page: Page) {
    const save = page.getByRole('button', { name: 'Save draft' });
    await save.click();
    await expect(
        page.locator('button.editor-post-saved-state, button:has-text("Saved")').first()
    ).toBeVisible({ timeout: 30_000 });
}

test.describe('Save & render — author selection persists across reload', () => {
    test('author-profile: insert + select + save round-trip', async ({ page }) => {
        await newPostFromMenu(page);
        await setPostTitle(page, 'APBL Profile Roundtrip ' + Date.now());
        await insertBlock(page, ' Author Profile');
        await pickFirstAuthor(page, 'Select Author');

        // Editor preview should now contain author content.
        const blockNode = page
            .frameLocator('iframe[name="editor-canvas"]')
            .locator('[data-type="author-profile-blocks/author-profile"]')
            .first();
        await expect(blockNode).toBeVisible();
        await expect(blockNode.locator('img').first()).toBeVisible();

        await saveDraft(page);

        // Reload and verify author still selected.
        await page.reload();
        await expect(page.locator('iframe[name="editor-canvas"]')).toBeAttached();
        const reloadedBlock = page
            .frameLocator('iframe[name="editor-canvas"]')
            .locator('[data-type="author-profile-blocks/author-profile"]')
            .first();
        await expect(reloadedBlock).toBeVisible();
        // Placeholder must NOT be back — selection persisted.
        await expect(reloadedBlock.getByText('Select an Author')).toHaveCount(0);
    });

    test('author-grid: insert + select + save round-trip', async ({ page }) => {
        await newPostFromMenu(page);
        await setPostTitle(page, 'APBL Grid Roundtrip ' + Date.now());
        await insertBlock(page, ' Author Grid');
        await pickFirstAuthor(page, 'Add Author');

        await saveDraft(page);
        await page.reload();

        const block = page
            .frameLocator('iframe[name="editor-canvas"]')
            .locator('[data-type="author-profile-blocks/author-grid"]')
            .first();
        await expect(block).toBeVisible();
    });

    test('author-list: insert + select + save round-trip', async ({ page }) => {
        await newPostFromMenu(page);
        await setPostTitle(page, 'APBL List Roundtrip ' + Date.now());
        await insertBlock(page, 'Author List');
        await pickFirstAuthor(page, 'Add Author');

        await saveDraft(page);
        await page.reload();

        const block = page
            .frameLocator('iframe[name="editor-canvas"]')
            .locator('[data-type="author-profile-blocks/author-list"]')
            .first();
        await expect(block).toBeVisible();
    });

    test('author-carousel: insert + select + save round-trip', async ({ page }) => {
        await newPostFromMenu(page);
        await setPostTitle(page, 'APBL Carousel Roundtrip ' + Date.now());
        await insertBlock(page, ' Author Carousel');
        await pickFirstAuthor(page, 'Add Author');

        await saveDraft(page);
        await page.reload();

        const block = page
            .frameLocator('iframe[name="editor-canvas"]')
            .locator('[data-type="author-profile-blocks/author-carousel"]')
            .first();
        await expect(block).toBeVisible();
    });
});
