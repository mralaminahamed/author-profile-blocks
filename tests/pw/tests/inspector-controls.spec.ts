import { test, expect, Page } from '@playwright/test';

/**
 * Inspector controls — every block must expose its sidebar settings panels,
 * tab strip, content-element toggles, and clear-author button. These tests
 * cover the right-hand "Block" inspector panel after a block is inserted.
 */

const BLOCKS = [
    {
        slug: 'author-profile',
        inserterLabel: ' Author Profile',
        title: 'Author Profile',
        single: true,
        addButton: 'Select Author',
    },
    {
        slug: 'author-grid',
        inserterLabel: ' Author Grid',
        title: 'Author Grid',
        single: false,
        addButton: 'Add Author',
    },
    {
        slug: 'author-list',
        inserterLabel: 'Author List',
        title: 'Author List',
        single: false,
        addButton: 'Add Author',
    },
    {
        slug: 'author-carousel',
        inserterLabel: ' Author Carousel',
        title: 'Author Carousel',
        single: false,
        addButton: 'Add Author',
    },
] as const;

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

const canvas = (page: Page) => page.frameLocator('iframe[name="editor-canvas"]');

test.describe('Inspector controls — settings sidebar exposes all panels', () => {
    for (const block of BLOCKS) {
        test(`${block.slug}: tab strip shows Content / Style / Layout / Advanced`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            await expect(page.getByRole('tab', { name: 'Content' })).toBeVisible();
            await expect(page.getByRole('tab', { name: 'Style' })).toBeVisible();
            await expect(page.getByRole('tab', { name: 'Layout' })).toBeVisible();
            await expect(page.getByRole('tab', { name: 'Advanced' })).toBeVisible();
        });

        test(`${block.slug}: Content tab shows Author Selection panel + element toggles`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            await page.getByRole('tab', { name: 'Content' }).click();

            await expect(page.getByRole('button', { name: 'Author Selection' })).toBeVisible();
            await expect(page.getByRole('button', { name: 'Content Elements' })).toBeVisible();
        });

        test(`${block.slug}: clear-author button only after picking an author`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            // No selection yet → no Clear button on single-author block.
            if (block.single) {
                await expect(
                    page.getByRole('button', { name: 'Clear Selected Author' })
                ).toHaveCount(0);
            }

            const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
            const button = canvas(page).getByRole('button', { name: block.addButton });
            await expect(select).toBeVisible();
            await expect.poll(
                async () => await select.locator('option').count(),
                { timeout: 15_000 }
            ).toBeGreaterThan(1);

            const optionValue = await select.locator('option').nth(1).getAttribute('value');
            expect(optionValue).toBeTruthy();
            await select.selectOption(optionValue!);
            await button.click();

            if (block.single) {
                await expect(
                    page.getByRole('button', { name: 'Clear Selected Author' })
                ).toBeVisible({ timeout: 15_000 });
            }
        });

        test(`${block.slug}: Style tab is reachable`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            const styleTab = page.getByRole('tab', { name: 'Style' });
            await styleTab.click();
            await expect(styleTab).toHaveAttribute('aria-selected', 'true');
        });

        test(`${block.slug}: Layout tab is reachable`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            const layoutTab = page.getByRole('tab', { name: 'Layout' });
            await layoutTab.click();
            await expect(layoutTab).toHaveAttribute('aria-selected', 'true');
        });

        test(`${block.slug}: Advanced tab is reachable`, async ({ page }) => {
            await newPostFromMenu(page);
            await insertBlock(page, block.inserterLabel);

            const advancedTab = page.getByRole('tab', { name: 'Advanced' });
            await advancedTab.click();
            await expect(advancedTab).toHaveAttribute('aria-selected', 'true');
        });
    }
});

test.describe('Inspector toggles — content elements wire to attributes', () => {
    test('author-profile: toggling Show Author Image flips the checkbox state', async ({ page }) => {
        await newPostFromMenu(page);
        await insertBlock(page, ' Author Profile');

        await page.getByRole('tab', { name: 'Content' }).click();

        const toggle = page.getByRole('checkbox', { name: 'Show Author Image' });
        const initial = await toggle.isChecked();

        await toggle.click();
        await expect(toggle).toBeChecked({ checked: ! initial });

        await toggle.click();
        await expect(toggle).toBeChecked({ checked: initial });
    });

    test('author-profile: Show Social Links toggle exists', async ({ page }) => {
        await newPostFromMenu(page);
        await insertBlock(page, ' Author Profile');

        await page.getByRole('tab', { name: 'Content' }).click();

        await expect(
            page.getByRole('checkbox', { name: 'Show Social Links' })
        ).toBeVisible();
    });

    test('author-profile: Lazy Load Images is on by default', async ({ page }) => {
        await newPostFromMenu(page);
        await insertBlock(page, ' Author Profile');

        await page.getByRole('tab', { name: 'Content' }).click();
        await expect(
            page.getByRole('checkbox', { name: 'Lazy Load Images' })
        ).toBeChecked();
    });
});
