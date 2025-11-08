import { Page } from '@playwright/test';

/**
 * Base page object with common functionality
 */
export class BasePage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    /**
     * Navigate to a URL
     */
    async goto(url: string): Promise<void> {
        await this.page.goto(url);
    }

    /**
     * Wait for element to be visible
     */
    async toBeVisible(selector: string, timeout: number = 10000): Promise<void> {
        await this.page.locator(selector).waitFor({ state: 'visible', timeout });
    }

    /**
     * Wait for element to be hidden
     */
    async toBeHidden(selector: string, timeout: number = 10000): Promise<void> {
        await this.page.locator(selector).waitFor({ state: 'hidden', timeout });
    }

    /**
     * Click on an element
     */
    async click(selector: string): Promise<void> {
        await this.page.locator(selector).click();
    }

    /**
     * Fill an input field
     */
    async fill(selector: string, value: string): Promise<void> {
        await this.page.locator(selector).fill(value);
    }

    /**
     * Get text content of an element
     */
    async getText(selector: string): Promise<string> {
        return await this.page.locator(selector).textContent() || '';
    }

    /**
     * Check if element exists
     */
    async exists(selector: string): Promise<boolean> {
        return await this.page.locator(selector).count() > 0;
    }

    /**
     * Upload file to input
     */
    async uploadFile(selector: string, filePath: string): Promise<void> {
        await this.page.locator(selector).setInputFiles(filePath);
    }

    /**
     * Wait for a specific amount of time
     */
    async wait(ms: number): Promise<void> {
        await this.page.waitForTimeout(ms);
    }

    /**
     * Take a screenshot
     */
    async screenshot(options?: { path?: string; fullPage?: boolean }): Promise<Buffer> {
        return await this.page.screenshot(options);
    }

    /**
     * Get current URL
     */
    async getCurrentURL(): Promise<string> {
        return this.page.url();
    }

    /**
     * Reload the page
     */
    async reload(): Promise<void> {
        await this.page.reload();
    }

    /**
     * Press a key
     */
    async pressKey(key: string): Promise<void> {
        await this.page.keyboard.press(key);
    }

    /**
     * Select option from dropdown
     */
    async selectOption(selector: string, value: string): Promise<void> {
        await this.page.locator(selector).selectOption(value);
    }

    /**
     * Check checkbox
     */
    async check(selector: string): Promise<void> {
        await this.page.locator(selector).check();
    }

    /**
     * Uncheck checkbox
     */
    async uncheck(selector: string): Promise<void> {
        await this.page.locator(selector).uncheck();
    }

    /**
     * Get attribute value
     */
    async getAttribute(selector: string, attribute: string): Promise<string | null> {
        return await this.page.locator(selector).getAttribute(attribute);
    }

    /**
     * Scroll element into view
     */
    async scrollIntoView(selector: string): Promise<void> {
        await this.page.locator(selector).scrollIntoViewIfNeeded();
    }

    /**
     * Hover over element
     */
    async hover(selector: string): Promise<void> {
        await this.page.locator(selector).hover();
    }

    /**
     * Double click element
     */
    async doubleClick(selector: string): Promise<void> {
        await this.page.locator(selector).dblclick();
    }

    /**
     * Right click element
     */
    async rightClick(selector: string): Promise<void> {
        await this.page.locator(selector).click({ button: 'right' });
    }

    /**
     * Drag and drop
     */
    async dragAndDrop(sourceSelector: string, targetSelector: string): Promise<void> {
        await this.page.locator(sourceSelector).dragTo(this.page.locator(targetSelector));
    }

    /**
     * Handle dialog (alert, confirm, prompt)
     */
    async handleDialog(action: 'accept' | 'dismiss' = 'accept', promptText?: string): Promise<void> {
        this.page.on('dialog', async dialog => {
            if (promptText && dialog.type() === 'prompt') {
                await dialog.accept(promptText);
            } else if (action === 'accept') {
                await dialog.accept();
            } else {
                await dialog.dismiss();
            }
        });
    }

    /**
     * Wait for network to be idle
     */
    async waitForNetworkIdle(timeout: number = 10000): Promise<void> {
        await this.page.waitForLoadState('networkidle', { timeout });
    }


}