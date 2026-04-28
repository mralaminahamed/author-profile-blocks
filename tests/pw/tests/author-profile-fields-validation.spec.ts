import { test, expect } from '@playwright/test';

/**
 * Author Profile Information field validation on the user-edit screen.
 *
 * - URL inputs accept only valid URLs (HTML5 type="url").
 * - Position is plain text and accepts any printable input.
 * - Member-since label accepts any plain text.
 * - HTML in the position field is escaped, not interpreted.
 */

const FIELDS = [
    { id: 'apbl_social_facebook',  type: 'url'  },
    { id: 'apbl_social_twitter',   type: 'url'  },
    { id: 'apbl_social_linkedin',  type: 'url'  },
    { id: 'apbl_social_instagram', type: 'url'  },
    { id: 'apbl_social_website',   type: 'url'  },
    { id: 'apbl_author_position',  type: 'text' },
    { id: 'apbl_member_since_label', type: 'text' },
];

test.describe('Author profile fields — input types and validation', () => {
    for (const field of FIELDS) {
        test(`${field.id} has correct input type "${field.type}"`, async ({ page }) => {
            await page.goto('/wp-admin/profile.php');
            await expect(page.locator(`#${field.id}`)).toHaveAttribute('type', field.type);
        });
    }

    test('position field round-trips a string with special characters', async ({ page }) => {
        const value = `Lead Engineer & "Architect" — ${Date.now()}`;

        await page.goto('/wp-admin/profile.php');
        await page.locator('#apbl_author_position').fill(value);
        await page.locator('#submit').click();
        await expect(page.locator('.notice-success, #message.updated')).toBeVisible();

        await page.goto('/wp-admin/profile.php');
        await expect(page.locator('#apbl_author_position')).toHaveValue(value);
    });

    test('position field with HTML is stored as plain text', async ({ page }) => {
        const dangerous = `<script>alert(${Date.now()})</script>X`;

        await page.goto('/wp-admin/profile.php');
        await page.locator('#apbl_author_position').fill(dangerous);
        await page.locator('#submit').click();
        await expect(page.locator('.notice-success, #message.updated')).toBeVisible();

        await page.goto('/wp-admin/profile.php');
        // sanitize_text_field strips tags but keeps the visible text.
        const stored = await page.locator('#apbl_author_position').inputValue();
        expect(stored).not.toContain('<script>');
        expect(stored).toContain('X');
    });

    test('clearing the member-since label resets to placeholder text on reload', async ({ page }) => {
        await page.goto('/wp-admin/profile.php');
        await page.locator('#apbl_member_since_label').fill('');
        await page.locator('#submit').click();
        await expect(page.locator('.notice-success, #message.updated')).toBeVisible();

        await page.goto('/wp-admin/profile.php');
        const value = await page.locator('#apbl_member_since_label').inputValue();
        // Empty string renders the default "Member since" via the placeholder
        // pre-fill on the form. Either empty or default is acceptable; lock
        // it in.
        expect(['', 'Member since']).toContain(value);
    });

    test('saving with malformed URL still succeeds (browser-side warning, server still saves esc_url_raw)', async ({ page }) => {
        await page.goto('/wp-admin/profile.php');
        await page.locator('#apbl_social_facebook').evaluate(
            (el: HTMLInputElement) => { el.removeAttribute('type'); }
        );
        await page.locator('#apbl_social_facebook').fill('not-a-url');
        await page.locator('#submit').click();
        await expect(page.locator('.notice-success, #message.updated')).toBeVisible();

        await page.goto('/wp-admin/profile.php');
        const stored = await page.locator('#apbl_social_facebook').inputValue();
        // esc_url_raw rewrites "not-a-url" → "" or "http://not-a-url"; either
        // way the original string must not survive verbatim.
        expect(stored).not.toBe('not-a-url');
    });
});
