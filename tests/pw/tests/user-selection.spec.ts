import { test, expect } from '@playwright/test';

/**
 * End-to-end tests for the in-editor user selection component (
 * AuthorBlockPlaceholder). Each block exposes a placeholder with a select
 * and an "Add Author"/"Select Author" button when no authors are saved on
 * the block attributes; selecting an author should clear the placeholder
 * and render the live preview.
 */

const BLOCKS = [
    {
        slug: 'author-profile',
        inserterLabel: ' Author Profile',
        addButton: 'Select Author',
        single: true,
    },
    {
        slug: 'author-grid',
        inserterLabel: ' Author Grid',
        addButton: 'Add Author',
        single: false,
    },
    {
        slug: 'author-list',
        inserterLabel: 'Author List',
        addButton: 'Add Author',
        single: false,
    },
    {
        slug: 'author-carousel',
        inserterLabel: ' Author Carousel',
        addButton: 'Add Author',
        single: false,
    },
] as const;

async function newPost(page) {
    // Navigate via the admin sidebar like a real editor would: Posts → Add Post.
    await page.goto('/wp-admin/');
    await page.locator('#menu-posts a:has-text("Posts")').first().hover();
    await page.locator('a[href="post-new.php"]').first().click();
    await page.waitForLoadState('domcontentloaded');

    // The editor canvas is inside an iframe; wait for it before continuing.
    await expect(
        page.locator('iframe[name="editor-canvas"]')
    ).toBeAttached({ timeout: 30_000 });
}

async function insertBlock(page, label: string) {
    await page.getByRole('button', { name: 'Block Inserter' }).click();
    const tile = page.getByRole('option', { name: label, exact: true });
    await tile.scrollIntoViewIfNeeded();
    await tile.click();
    // Close the inserter so it doesn't shadow the canvas.
    await page.getByRole('button', { name: 'Close Block Inserter' }).click().catch(() => undefined);
}

function canvas(page) {
    return page.frameLocator('iframe[name="editor-canvas"]');
}

test.describe('In-editor user selection — picker is functional', () => {
    for (const block of BLOCKS) {
        test(`${block.slug}: placeholder exposes select + add button`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, block.inserterLabel);

            const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
            const button = canvas(page).getByRole('button', { name: block.addButton });

            await expect(select).toBeVisible();
            await expect(button).toBeVisible();
        });

        test(`${block.slug}: select lists at least one real author`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, block.inserterLabel);

            const select = canvas(page).getByRole('combobox', { name: 'AUTHOR' });
            await expect(select).toBeVisible();

            // Wait for the REST fetch to populate options. The first option is
            // the "Select an author…" placeholder, so >1 means real users.
            await expect.poll(
                async () => await select.locator('option').count(),
                { timeout: 15_000 }
            ).toBeGreaterThan(1);
        });

        test(`${block.slug}: choosing an author dismisses the placeholder`, async ({ page }) => {
            await newPost(page);
            await insertBlock(page, block.inserterLabel);

            const c       = canvas(page);
            const select  = c.getByRole('combobox', { name: 'AUTHOR' });
            const button  = c.getByRole('button', { name: block.addButton });
            const heading = c.getByText('Select an Author', { exact: false });

            await expect(select).toBeVisible();
            await expect.poll(
                async () => await select.locator('option').count(),
                { timeout: 15_000 }
            ).toBeGreaterThan(1);

            // Pick the first real author (skip the "Select an author…" placeholder).
            const realOptionValue = await select
                .locator('option')
                .nth(1)
                .getAttribute('value');
            expect(realOptionValue, 'a real author option must exist').toBeTruthy();

            await select.selectOption(realOptionValue!);
            await button.click();

            // After selection, the placeholder must disappear and the block
            // should render its preview content (admin avatar / heading).
            await expect(heading).toHaveCount(0, { timeout: 15_000 });

            // Inspector exposes a "Clear Selected Author" button only for
            // the single-author block.
            if (block.single) {
                await expect(
                    page.getByRole('button', { name: 'Clear Selected Author' })
                ).toBeVisible();
            }
        });
    }
});
