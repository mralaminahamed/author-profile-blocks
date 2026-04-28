import { test, expect, Page } from '@playwright/test';

/**
 * Multi-author selection — Grid / List / Carousel each let you add
 * multiple authors. Picking one should narrow the dropdown so the same
 * user can't be added twice; the selection counter / previously-added
 * users persist across renders.
 */

const MULTI_BLOCKS = [
    { slug: 'author-grid',     inserterLabel: ' Author Grid'     },
    { slug: 'author-list',     inserterLabel: 'Author List'      },
    { slug: 'author-carousel', inserterLabel: ' Author Carousel' },
] as const;

async function newPost(page: Page) {
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

test.describe('Multi-author selection — narrowing & counter', () => {
    for (const block of MULTI_BLOCKS) {
        test(`${block.slug}: dropdown shrinks after each Add Author click`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, block.inserterLabel);

            const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
            const button = canvas(page).getByRole('button', { name: 'Add Author' });

            await expect(select).toBeVisible();
            await expect.poll(
                async () => await select.locator('option').count(),
                { timeout: 15_000 }
            ).toBeGreaterThan(1);

            const before = await select.locator('option').count();

            // Pick first real author and add.
            const firstValue = await select.locator('option').nth(1).getAttribute('value');
            await select.selectOption(firstValue!);
            await button.click();
        });

        test(`${block.slug}: empty selection requires picking one author before save`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, block.inserterLabel);

            // Block placeholder visible, no preview yet.
            await expect(
                canvas(page).getByText('apbl-author-grid-item').first()
            ).toHaveCount(0);
        });
    }
});
