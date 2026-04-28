import { Page, Locator, FrameLocator, expect } from '@playwright/test';

/**
 * Page object for the modern WordPress block editor (Gutenberg).
 *
 * The editor canvas lives inside an iframe with name="editor-canvas" in
 * recent WP versions, so canvas-side queries (title, blocks) go through a
 * FrameLocator while toolbar/inspector queries stay on the top page.
 */
export class EditorPage {
    readonly page: Page;
    readonly canvas: FrameLocator;

    constructor(page: Page) {
        this.page   = page;
        this.canvas = page.frameLocator('iframe[name="editor-canvas"]');
    }

    /** Open a new post in the block editor and dismiss any welcome modal. */
    async openNewPost(title = 'APBL E2E Test'): Promise<void> {
        await this.page.goto('/wp-admin/post-new.php');
        await this.page.waitForLoadState('domcontentloaded');
        await this.dismissWelcomeGuide();

        // Wait for the canvas iframe to attach.
        await this.page.locator('iframe[name="editor-canvas"]').waitFor({ state: 'attached', timeout: 30_000 });

        // Title field lives inside the canvas iframe.
        const titleInput = this.canvas.locator(
            '.editor-post-title, .editor-post-title__input, [aria-label="Add title"]'
        ).first();
        await titleInput.click();
        await this.page.keyboard.type(title);
    }

    /** Close the welcome / "what's new" modal if present. */
    async dismissWelcomeGuide(): Promise<void> {
        const close = this.page.locator(
            '[aria-label="Close"][class*="welcome-guide"], button[aria-label="Close dialog"]'
        );
        if (await close.first().isVisible().catch(() => false)) {
            await close.first().click().catch(() => undefined);
        }
    }

    /** Open the document-tools block inserter. */
    async openInserter(): Promise<Locator> {
        const toggle = this.page.locator(
            'button[aria-label="Toggle block inserter"], button[aria-label="Block Inserter"]'
        ).first();
        await toggle.click();

        const search = this.page.locator(
            'input[placeholder="Search"], .block-editor-inserter__search input'
        ).first();
        await search.waitFor({ state: 'visible' });
        return search;
    }

    /** Insert a block by its registered slug (e.g. "author-profile"). */
    async insertBlock(blockSlug: string, blockTitle?: string): Promise<void> {
        const search = await this.openInserter();
        await search.fill(blockTitle ?? blockSlug);

        const tile = this.page.locator(
            `.block-editor-block-types-list__item.editor-block-list-item-${blockSlug}`
        ).first();
        await tile.click();

        // Close the inserter so the inspector is reachable.
        await this.page.keyboard.press('Escape');
    }

    /** Locate an inserter tile by registered block slug. */
    inserterTile(blockSlug: string): Locator {
        return this.page.locator(
            `.block-editor-block-types-list__item.editor-block-list-item-${blockSlug}`
        ).first();
    }

    /** Locate the inserted block on the canvas by its registered name. */
    block(blockName: `author-profile-blocks/${string}`): Locator {
        return this.canvas.locator(`[data-type="${blockName}"]`);
    }

    /** Click the block on the canvas to focus it (so inspector loads). */
    async selectBlockOnCanvas(blockName: `author-profile-blocks/${string}`): Promise<void> {
        await this.block(blockName).first().click();
    }

    /** Open the document/block sidebar if it is collapsed and switch to "Block" tab. */
    async openSettingsSidebar(): Promise<void> {
        const sidebar = this.page.locator('.interface-interface-skeleton__sidebar');
        if (!(await sidebar.first().isVisible().catch(() => false))) {
            const toggle = this.page.locator(
                'button[aria-label="Settings"], button[aria-label="Show sidebar"]'
            ).first();
            if (await toggle.isVisible().catch(() => false)) {
                await toggle.click();
            }
        }
        const blockTab = this.page.getByRole('tab', { name: 'Block' });
        if (await blockTab.isVisible().catch(() => false)) {
            await blockTab.click();
        }
    }

    /** Save the post as a draft and assert success. */
    async saveDraft(): Promise<void> {
        const saveButton = this.page.locator(
            'button.editor-post-save-draft, button[aria-label="Save draft"], button:has-text("Save draft")'
        ).first();
        await saveButton.click();
        await expect(
            this.page.locator(
                'button.editor-post-saved-state.is-saved, button:has-text("Saved")'
            ).first()
        ).toBeVisible({ timeout: 30_000 });
    }

    /** Locator for any inspector control panel by its visible title. */
    inspectorPanel(title: string): Locator {
        return this.page.locator(
            `.components-panel__body:has(.components-panel__body-title:has-text("${title}")), ` +
            `.components-panel__body:has(button:has-text("${title}"))`
        );
    }

    /** Expand a collapsed inspector panel by title (no-op if already open). */
    async expandPanel(title: string): Promise<void> {
        const panel  = this.inspectorPanel(title).first();
        const button = panel.locator(
            'button.components-panel__body-toggle, button:has-text("' + title + '")'
        ).first();
        const expanded = await button.getAttribute('aria-expanded');
        if (expanded === 'false') {
            await button.click();
        }
    }
}
