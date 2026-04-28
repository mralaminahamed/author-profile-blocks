import { test, expect } from '@playwright/test';
import { insertBlock, newPost, openInspectorTab } from '@utils/editorHelpers';
import { flipToggle } from '@utils/controlHelpers';

const LABEL = 'Author List';

test.describe('Author List — every inspector control is functional', () => {

    /* ------------------------------ Content tab ----------------------------- */

    const CONTENT_TOGGLES = [
        'Show Author Image',
        'Show Position/Role',
        'Show Email',
        'Show Description',
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

    /* ------------------------------ Layout tab ----------------------------- */

    test('Layout: Layout Preset selector visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Layout Preset').first()).toBeVisible();
    });

    test('Layout: Display Style selector visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Display Style').first()).toBeVisible();
    });

    test('Layout: List Style selector visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('List Style').first()).toBeVisible();
    });

    test('Layout: spacing controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        for (const label of ['Block Padding', 'Margin', 'Section Spacing', 'Container Width', 'Text Alignment']) {
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

    /* ----------------------------- Advanced tab ---------------------------- */

    test('Advanced: Custom CSS Class field accepts text', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Advanced');

        const field = page.getByLabel('Custom CSS Class').first();
        await field.fill('list-test-class');
        await expect(field).toHaveValue('list-test-class');
    });
});
