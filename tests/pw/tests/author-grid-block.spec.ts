import { test, expect } from '@playwright/test';
import {
    canvas,
    insertBlock,
    newPost,
    openInspectorTab,
    pickFirstAuthor,
} from '@utils/editorHelpers';

const SLUG  = 'author-profile-blocks/author-grid';
const LABEL = ' Author Grid';

test.describe('Author Grid block', () => {
    test('inserts and shows placeholder picker', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(canvas(page).getByRole('combobox', { name: 'AUTHOR' })).toBeVisible();
        await expect(canvas(page).getByRole('button', { name: 'Add Author' })).toBeVisible();
    });

    test('after picking one author, the placeholder is replaced by preview', async ({ page }) => {
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

    test('Layout tab is reachable and selected after click', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Layout' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('Style tab is reachable and selected after click', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Style' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('Advanced tab is reachable and selected after click', async ({ page }) => {
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

    test('Clear Authors button appears after selecting an author', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await pickFirstAuthor(page, 'Add Author');

        const clearBtn = page.getByRole('button', { name: /Clear Authors/i });
        await expect(clearBtn).toBeVisible({ timeout: 15_000 });
    });

    test('grid block name shown in editor footer', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(
            page.locator('.edit-post-block-breadcrumb, .editor-footer').filter({ hasText: 'Author Grid' }).first()
        ).toBeVisible();
    });
});
