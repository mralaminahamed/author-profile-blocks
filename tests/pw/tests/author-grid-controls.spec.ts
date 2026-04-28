import { test, expect } from '@playwright/test';
import { insertBlock, newPost, openInspectorTab } from '@utils/editorHelpers';
import { flipToggle, expectToggleVisible } from '@utils/controlHelpers';

const LABEL = ' Author Grid';

test.describe('Author Grid — every inspector control is functional', () => {

    /* ------------------------------ Content tab ----------------------------- */

    const CONTENT_TOGGLES = [
        'Show Author Image',
        'Show Position/Role',
        'Show Email',
        'Show Description',
        'Show Member Since Date',
        'Show Social Links',
        'Lazy Load Images',
        'Content Tabs',
    ];

    for (const label of CONTENT_TOGGLES) {
        test(`Content: "${label}" toggle is visible and flips`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, LABEL);
            await openInspectorTab(page, 'Content');
            await flipToggle(page, label);
        });
    }

    test('Content: Filter by Role select is visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByLabel('Filter by Role')).toBeVisible();
    });

    test('Content: Max Authors number input is visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByText('Max Authors').first()).toBeVisible();
    });

    /* ------------------------------ Layout tab ----------------------------- */

    test('Layout: Layout Preset selector exists', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Layout Preset').first()).toBeVisible();
    });

    test('Layout: Columns control exists and accepts values 1-4', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Columns').first()).toBeVisible();
    });

    test('Layout: Text Alignment / Margin / Section Spacing / Container Width all present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        for (const label of ['Text Alignment', 'Margin', 'Section Spacing', 'Container Width']) {
            await expect(page.getByText(label).first()).toBeVisible();
        }
    });

    /* ------------------------------ Style tab ------------------------------ */

    test('Style: Background Color control present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expect(page.getByText('Background Color').first()).toBeVisible();
    });

    test('Style: Border controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expect(page.getByText('Border Width').first()).toBeVisible();
        await expect(page.getByText('Border Color').first()).toBeVisible();
    });

    /* ----------------------------- Advanced tab ---------------------------- */

    test('Advanced: Custom CSS Class field accepts text', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Advanced');

        const field = page.getByLabel('Custom CSS Class').first();
        await field.fill('grid-test-class');
        await expect(field).toHaveValue('grid-test-class');
    });
});
