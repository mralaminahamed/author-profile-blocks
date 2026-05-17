import { test, expect, Page } from '@playwright/test';
import { canvas, insertBlock, newPost, pickFirstAuthor } from '@utils/editorHelpers';

/**
 * Verify that `apbl-error-message` is suppressed on the frontend.
 *
 * PHP render_callback returns '' (not error HTML) when REST_REQUEST is false
 * (i.e. any normal page view). These tests exercise that path end-to-end:
 * insert a block via the editor, save, then visit the frontend preview and
 * assert no error div leaks out.
 *
 * The inverse — error IS visible inside the editor — is covered by the PHP
 * integration test suite (simulate_editor_context / REST_REQUEST path).
 */

const BLOCKS = [
    { slug: 'author-profile', label: ' Author Profile', addButton: 'Select Author' as const },
    { slug: 'author-grid',    label: ' Author Grid',    addButton: 'Add Author' as const },
    { slug: 'author-list',    label: 'Author List',     addButton: 'Add Author' as const },
    { slug: 'author-carousel', label: ' Author Carousel', addButton: 'Add Author' as const },
] as const;

async function saveDraftAndGetPreviewUrl(page: Page): Promise<string> {
    await page
        .locator(
            'button.editor-post-save-draft, button[aria-label="Save draft"], button:has-text("Save draft")'
        )
        .first()
        .click();

    await expect(
        page.locator('button.editor-post-saved-state, button:has-text("Saved")').first()
    ).toBeVisible({ timeout: 30_000 });

    const match = page.url().match(/[?&]post=(\d+)/);
    if (!match) throw new Error(`No post ID in editor URL: ${page.url()}`);

    const origin = new URL(page.url()).origin;
    return `${origin}/?p=${match[1]}&preview=true`;
}

test.describe('Error-message visibility', () => {
    test.describe('frontend renders blank when no author selected', () => {
        for (const block of BLOCKS) {
            test(block.slug, async ({ page }) => {
                await newPost(page);
                await insertBlock(page, block.label);
                // Save without picking an author — block attrs stay empty.
                const url = await saveDraftAndGetPreviewUrl(page);

                await page.goto(url);
                await page.waitForLoadState('domcontentloaded');

                // apbl-error-message must NEVER appear on the public-facing frontend.
                await expect(page.locator('.apbl-error-message')).toHaveCount(0);
            });
        }
    });

    test.describe('frontend renders content without error when author is set', () => {
        for (const block of BLOCKS) {
            test(block.slug, async ({ page }) => {
                await newPost(page);
                await insertBlock(page, block.label);
                await pickFirstAuthor(page, block.addButton);
                const url = await saveDraftAndGetPreviewUrl(page);

                await page.goto(url);
                await page.waitForLoadState('domcontentloaded');

                await expect(page.locator('.apbl-error-message')).toHaveCount(0);

                // At least one author element must be visible.
                await expect(
                    page.locator('[class*="apbl-author"]').first()
                ).toBeVisible();
            });
        }
    });

    test.describe('editor still shows placeholder (not error) when no author selected', () => {
        for (const block of BLOCKS) {
            test(block.slug, async ({ page }) => {
                await newPost(page);
                await insertBlock(page, block.label);

                // React placeholder component must appear — not an error div.
                await expect(
                    canvas(page).getByRole('combobox', { name: 'AUTHOR' })
                ).toBeVisible();
                await expect(
                    canvas(page).locator('.apbl-error-message')
                ).toHaveCount(0);
            });
        }
    });
});
