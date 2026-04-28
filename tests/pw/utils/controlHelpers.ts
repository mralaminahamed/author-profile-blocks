import { Page, Locator, expect } from '@playwright/test';

/**
 * Inspector-control helpers — locate WP component controls by their visible
 * label and exercise them.
 *
 * The block-editor renders most controls as <label>+input pairs but some
 * (RangeControl) live inside a labelled fieldset. The helpers prefer the
 * accessible role, falling back to label-text matches.
 */

/** Toggle a ToggleControl by its label and assert the state flipped. */
export async function flipToggle(page: Page, label: string): Promise<void> {
    const toggle = page.getByRole('checkbox', { name: label });
    await expect(toggle, `${label} toggle should be visible`).toBeVisible();
    const before = await toggle.isChecked();
    await toggle.click();
    await expect(toggle).toBeChecked({ checked: !before });
}

/** Assert a checkbox is visible (without flipping it). */
export async function expectToggleVisible(page: Page, label: string): Promise<void> {
    await expect(
        page.getByRole('checkbox', { name: label }),
        `${label} toggle should be visible`,
    ).toBeVisible();
}

/** Fill a text/number input by its label. */
export async function fillField(
    page: Page,
    label: string,
    value: string,
): Promise<void> {
    const field = page.getByLabel(label).first();
    await expect(field, `${label} field should be visible`).toBeVisible();
    await field.fill(value);
}

/** Pick an option in a SelectControl by its label. */
export async function selectOption(
    page: Page,
    label: string,
    optionLabel: string,
): Promise<void> {
    const select = page.getByLabel(label).first();
    await expect(select, `${label} select should be visible`).toBeVisible();
    await select.selectOption({ label: optionLabel });
}

/** Click a button by its accessible name and wait for it to settle. */
export async function clickButton(page: Page, name: string): Promise<void> {
    const button = page.getByRole('button', { name });
    await expect(button, `${name} button should be visible`).toBeVisible();
    await button.click();
}

/** Locator for a control labelled with the given text (input, select, etc). */
export function controlByLabel(page: Page, label: string): Locator {
    return page.getByLabel(label).first();
}

/** Expand a panel by its title, no-op if already expanded. */
export async function expandPanel(page: Page, title: string): Promise<void> {
    const button = page.getByRole('button', { name: title });
    const expanded = await button.getAttribute('aria-expanded');
    if (expanded === 'false') {
        await button.click();
    }
}
