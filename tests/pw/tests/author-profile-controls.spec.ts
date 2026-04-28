import { test, expect } from '@playwright/test';
import { insertBlock, newPost, openInspectorTab } from '@utils/editorHelpers';
import {
    flipToggle,
    expectToggleVisible,
    expandPanel,
} from '@utils/controlHelpers';

const LABEL = ' Author Profile';

test.describe('Author Profile — every inspector control is functional', () => {

    /* ------------------------------ Content tab ----------------------------- */

    const CONTENT_TOGGLES = [
        'Show Author Image',
        'Show Author Email',
        'Show Author Description',
        'Show Member Since Date',
        'Show More Section',
        'Show Social Links',
        'Lazy Load Images',
        'Organize Content in Tabs',
    ];

    for (const label of CONTENT_TOGGLES) {
        test(`Content: "${label}" toggle is visible and flips`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, LABEL);
            await openInspectorTab(page, 'Content');
            await flipToggle(page, label);
        });
    }

    test('Content: Social Links section reveals platform toggles when enabled', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await flipToggle(page, 'Show Social Links');

        for (const platform of ['Facebook', 'Twitter', 'LinkedIn', 'Instagram', 'YouTube', 'GitHub', 'Website']) {
            await expectToggleVisible(page, platform);
        }
    });

    /* ------------------------------ Style tab ------------------------------ */

    test('Style: Block Style Preset selector exists', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expect(page.getByText('Block Style Preset')).toBeVisible();
    });

    test('Style: Background Color control panel reachable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expect(page.getByText('Background Color').first()).toBeVisible();
    });

    test('Style: Border Width / Border Color / Border Radius controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expect(page.getByText('Border Width').first()).toBeVisible();
        await expect(page.getByText('Border Color').first()).toBeVisible();
        await expect(page.getByText('Border Radius').first()).toBeVisible();
    });

    test('Style: Box Shadow toggle exists', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await expectToggleVisible(page, 'Box Shadow');
    });

    test('Style: Box Shadow toggle reveals shadow controls when enabled', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Style');

        await flipToggle(page, 'Box Shadow');

        await expect(page.getByText('Blur Radius').first()).toBeVisible();
        await expect(page.getByText('Spread').first()).toBeVisible();
        await expect(page.getByText('Shadow Color').first()).toBeVisible();
    });

    /* ------------------------------ Layout tab ----------------------------- */

    test('Layout: Content Layout selector exists', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Content Layout').first()).toBeVisible();
    });

    test('Layout: Text Alignment buttons render', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Text Alignment').first()).toBeVisible();
    });

    test('Layout: Padding & Margin & Section Spacing controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Padding').first()).toBeVisible();
        await expect(page.getByText('Margin').first()).toBeVisible();
        await expect(page.getByText('Section Spacing').first()).toBeVisible();
    });

    test('Layout: Container Width control present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Container Width').first()).toBeVisible();
    });

    /* ----------------------------- Advanced tab ---------------------------- */

    test('Advanced: Custom CSS Class field present and editable', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Advanced');

        const field = page.getByLabel('Custom CSS Class').first();
        await expect(field).toBeVisible();
        await field.fill('my-custom-class');
        await expect(field).toHaveValue('my-custom-class');
    });
});
