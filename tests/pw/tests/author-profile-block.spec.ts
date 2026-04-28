import { test, expect } from '@playwright/test';
import {
    canvas,
    insertBlock,
    newPost,
    openInspectorTab,
    pickFirstAuthor,
} from '@utils/editorHelpers';

const SLUG  = 'author-profile-blocks/author-profile';
const LABEL = ' Author Profile';

test.describe('Author Profile block', () => {
    test('inserts and shows the placeholder picker', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(canvas(page).getByText('Select an Author')).toBeVisible();
        await expect(canvas(page).getByRole('combobox', { name: 'AUTHOR' })).toBeVisible();
    });

    test('after picking, preview shows avatar + name + position', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await pickFirstAuthor(page, 'Select Author');

        const node = canvas(page).locator(`[data-type="${SLUG}"]`).first();
        await expect(node.locator('img').first()).toBeVisible();
        await expect(node.locator('h3, h4').first()).toBeVisible();
    });

    test('Clear Selected Author returns the picker', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await pickFirstAuthor(page, 'Select Author');

        await page.getByRole('button', { name: 'Clear Selected Author' }).click();

        await expect(canvas(page).getByText('Select an Author')).toBeVisible();
    });

    test('toggling Show Author Image flips state', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        const toggle = page.getByRole('checkbox', { name: 'Show Author Image' });
        const before = await toggle.isChecked();

        await toggle.click();
        await expect(toggle).toBeChecked({ checked: ! before });
    });

    test('toggling Show Author Email flips state', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        const toggle = page.getByRole('checkbox', { name: 'Show Author Email' });
        const before = await toggle.isChecked();

        await toggle.click();
        await expect(toggle).toBeChecked({ checked: ! before });
    });

    test('toggling Show Author Description flips state', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        const toggle = page.getByRole('checkbox', { name: 'Show Author Description' });
        const before = await toggle.isChecked();

        await toggle.click();
        await expect(toggle).toBeChecked({ checked: ! before });
    });

    test('Show More Section toggle exists and starts off by default', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        const toggle = page.getByRole('checkbox', { name: 'Show More Section' });
        await expect(toggle).toBeVisible();
        await expect(toggle).not.toBeChecked();
    });

    test('Show Social Links toggle exists', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(
            page.getByRole('checkbox', { name: 'Show Social Links' })
        ).toBeVisible();
    });

    test('Show Member Since Date is on by default', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(
            page.getByRole('checkbox', { name: 'Show Member Since Date' })
        ).toBeChecked();
    });

    test('Lazy Load Images is on by default', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(
            page.getByRole('checkbox', { name: 'Lazy Load Images' })
        ).toBeChecked();
    });

    test('Organize Content in Tabs starts off by default', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(
            page.getByRole('checkbox', { name: 'Organize Content in Tabs' })
        ).not.toBeChecked();
    });

    test('block toolbar exposes Align + Align text controls', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(page.getByRole('button', { name: 'Align', exact: true })).toBeVisible();
        await expect(page.getByRole('button', { name: 'Align text' })).toBeVisible();
    });

    test('block name shown in editor footer', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);

        await expect(
            page.locator('.edit-post-block-breadcrumb, .editor-footer').filter({ hasText: 'Author Profile' }).first()
        ).toBeVisible();
    });
});
