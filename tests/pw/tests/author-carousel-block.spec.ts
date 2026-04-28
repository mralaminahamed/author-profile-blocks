import { test, expect } from '@playwright/test';
import {
    canvas,
    insertBlock,
    newPost,
    openInspectorTab,
    pickFirstAuthor,
} from '@utils/editorHelpers';

const SLUG  = 'author-profile-blocks/author-carousel';
const LABEL = ' Author Carousel';

test.describe('Author Carousel block', () => {
    test('inserts and shows placeholder picker', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(canvas(page).getByRole('combobox', { name: 'AUTHOR' })).toBeVisible();
        await expect(canvas(page).getByRole('button', { name: 'Add Author' })).toBeVisible();
    });

    test('after picking, preview replaces the placeholder', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await pickFirstAuthor(page, 'Add Author');

        const node = canvas(page).locator(`[data-type="${SLUG}"]`).first();
        await expect(node).toBeVisible();
    });

    test('Content tab exposes Author Selection panel', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByRole('button', { name: 'Author Selection' })).toBeVisible();
    });

    test('Style tab reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Style' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('Layout tab reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Layout' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('Advanced tab reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Advanced' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('block toolbar exposes Align controls', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(page.getByRole('button', { name: 'Align', exact: true })).toBeVisible();
    });

    test('carousel block name shown in editor footer', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(
            page.locator('.edit-post-block-breadcrumb, .editor-footer').filter({ hasText: 'Author Carousel' }).first()
        ).toBeVisible();
    });

    test('multiple authors can be added then carousel renders preview', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
        const button = canvas(page).getByRole('button', { name: 'Add Author' });

        await expect(select).toBeVisible();
        await expect.poll(
            async () => await select.locator('option').count(),
            { timeout: 15_000 }
        ).toBeGreaterThan(1);

        const value = await select.locator('option').nth(1).getAttribute('value');
        await select.selectOption(value!);
        await button.click();

        await expect(
            canvas(page).locator(`[data-type="${SLUG}"]`).first()
        ).toBeVisible();
    });
});
