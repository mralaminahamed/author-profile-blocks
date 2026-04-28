import { test, expect } from '@playwright/test';
import { EditorPage } from '@pages/editorPage';

const BLOCKS = [
    { slug: 'author-profile',  title: 'Author Profile',  fullName: 'author-profile-blocks/author-profile'  },
    { slug: 'author-grid',     title: 'Author Grid',     fullName: 'author-profile-blocks/author-grid'     },
    { slug: 'author-list',     title: 'Author List',     fullName: 'author-profile-blocks/author-list'     },
    { slug: 'author-carousel', title: 'Author Carousel', fullName: 'author-profile-blocks/author-carousel' },
] as const;

test.describe('Block inserter — all four blocks visible & insertable', () => {
    test('inserter exposes every Author Profile Block', async ({ page }) => {
        const editor = new EditorPage(page);
        await editor.openNewPost('Inserter Visibility');

        const search = await editor.openInserter();
        await search.fill('Author');

        for (const { slug, title } of BLOCKS) {
            const tile = editor.inserterTile(slug);
            await expect(tile, `${title} should appear in the inserter`).toBeVisible();
            await expect(tile).toContainText(title);
        }
    });

    for (const block of BLOCKS) {
        test(`inserts "${block.title}" onto the canvas`, async ({ page }) => {
            const editor = new EditorPage(page);
            await editor.openNewPost(`Insert ${block.title}`);
            await editor.insertBlock(block.slug, block.title);

            await expect(editor.block(block.fullName).first())
                .toBeVisible();
        });
    }
});
