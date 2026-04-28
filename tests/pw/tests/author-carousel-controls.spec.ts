import { test, expect } from '@playwright/test';
import { insertBlock, newPost, openInspectorTab } from '@utils/editorHelpers';
import { flipToggle } from '@utils/controlHelpers';

const LABEL = ' Author Carousel';

test.describe('Author Carousel — every inspector control is functional', () => {

    /* ------------------------------ Content tab ----------------------------- */

    const CONTENT_TOGGLES = [
        'Show Avatar',
        'Show Email',
        'Show Description',
        'Show Position',
        'Show Social Links',
        'Show Registration Date',
        'Autoplay',
        'Show Dots',
        'Show Arrows',
        'Infinite Loop',
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

    test('Content: Slides to Show control visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByText('Slides to Show').first()).toBeVisible();
    });

    test('Content: Max Authors control visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByText('Max Authors').first()).toBeVisible();
    });

    test('Content: Author Role select visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        await expect(page.getByLabel('Author Role')).toBeVisible();
    });

    test('Content: Autoplay reveals Autoplay Speed when enabled', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Content');

        // Autoplay defaults on; if currently off, flip on first.
        const autoplay = page.getByRole('checkbox', { name: 'Autoplay' });
        if (!(await autoplay.isChecked())) {
            await autoplay.click();
        }

        await expect(page.getByText('Autoplay Speed (ms)').first()).toBeVisible();
    });

    /* ------------------------------ Layout tab ----------------------------- */

    test('Layout: Layout Style + Layout Preset selectors visible', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Layout Style').first()).toBeVisible();
        await expect(page.getByText('Layout Preset').first()).toBeVisible();
    });

    test('Layout: spacing controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        for (const label of ['Padding', 'Slide Spacing', 'Section Spacing', 'Margin', 'Container Width']) {
            await expect(page.getByText(label).first()).toBeVisible();
        }
    });

    test('Layout: border controls present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        for (const label of ['Border Radius', 'Border Width', 'Border Color']) {
            await expect(page.getByText(label).first()).toBeVisible();
        }
    });

    test('Layout: Text Alignment control present', async ({ page }) => {
        await newPost(page);
        await insertBlock(page, LABEL);
        await openInspectorTab(page, 'Layout');

        await expect(page.getByText('Text Alignment').first()).toBeVisible();
    });

    /* ------------------------------ Style tab ------------------------------ */

    test('Style: Background Color visible', async ({ page }) => {
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
        await field.fill('carousel-test-class');
        await expect(field).toHaveValue('carousel-test-class');
    });
});
