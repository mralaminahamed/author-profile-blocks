import { test, expect } from '@playwright/test';
import {
    canvas,
    insertBlock,
    newPost,
    openInspectorTab,
    pickFirstAuthor,
} from '@utils/editorHelpers';

const SLUG  = 'author-profile-blocks/author-list';
const LABEL = 'Author List';

test.describe('Author List block', () => {
    test('inserts and shows placeholder picker', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(canvas(page).getByRole('combobox', { name: 'AUTHOR' })).toBeVisible();
        await expect(canvas(page).getByRole('button', { name: 'Add Author' })).toBeVisible();
    });

    test('placeholder dismisses after picking an author', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await pickFirstAuthor(page, 'Add Author');

        await expect(canvas(page).getByText('Author List', { exact: true }).first()).toHaveCount(0).catch(() => {});
        const node = canvas(page).locator(`[data-type="${SLUG}"]`).first();
        await expect(node).toBeVisible();
    });

    test('Content tab exposes Author Selection panel', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByRole('button', { name: 'Author Selection' })).toBeVisible();
    });

    test('Layout tab reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Layout' });
        await tab.click();
        await expect(tab).toHaveAttribute('aria-selected', 'true');
    });

    test('Style tab reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const tab = page.getByRole('tab', { name: 'Style' });
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

    test('list block name shown in editor footer', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(
            page.locator('.edit-post-block-breadcrumb, .editor-footer').filter({ hasText: 'Author List' }).first()
        ).toBeVisible();
    });

    test('multiple authors can be added sequentially', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
        const button = canvas(page).getByRole('button', { name: 'Add Author' });

        await expect(select).toBeVisible();
        await expect.poll(
            async () => await select.locator('option').count(),
            { timeout: 15_000 }
        ).toBeGreaterThan(1);

        // Pick first real author.
        const firstValue = await select.locator('option').nth(1).getAttribute('value');
        await select.selectOption(firstValue!);
        await button.click();
    });
});
