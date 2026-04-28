import { test, expect } from '@playwright/test';

/**
 * Verify the plugin's "Author Profile Information" section renders on the
 * user-edit screen with every field visible and editable.
 */
test.describe('User profile — Author Profile Information section', () => {
    test('section heading and description visible on Your Profile', async ({ page }) => {
        await page.goto('/wp-admin/profile.php');

        await expect(
            page.getByRole('heading', { name: 'Author Profile Information' })
        ).toBeVisible();

        await expect(
            page.getByText('These fields are used by the Author Profile Blocks plugin')
        ).toBeVisible();
    });

    test('all author profile fields visible and editable', async ({ page }) => {
        await page.goto('/wp-admin/profile.php');

        await expect(page.locator('#apbl_author_position')).toBeVisible();
        await expect(page.locator('#apbl_author_position')).toBeEditable();

        await expect(page.locator('#apbl_member_since_label')).toBeVisible();
        await expect(page.locator('#apbl_member_since_label')).toBeEditable();

        await expect(page.locator('#apbl_social_facebook')).toBeVisible();
        await expect(page.locator('#apbl_social_twitter')).toBeVisible();
        await expect(page.locator('#apbl_social_linkedin')).toBeVisible();
        await expect(page.locator('#apbl_social_instagram')).toBeVisible();
        await expect(page.locator('#apbl_social_website')).toBeVisible();
    });

    test('TinyMCE description editor renders', async ({ page }) => {
        await page.goto('/wp-admin/profile.php');

        // wp_editor renders either the visual TinyMCE iframe or a textarea
        // when teeny mode is forced. Either is acceptable.
        const wrap = page.locator('#wp-apbl_author_description-wrap');
        await expect(wrap).toBeVisible();
    });

    test('saving the section persists field values', async ({ page }) => {
        const stamp = Date.now();
        const position = `Lead Engineer ${stamp}`;
        const memberSince = `Crafting since ${stamp}`;
        const facebook = `https://facebook.com/test-${stamp}`;

        await page.goto('/wp-admin/profile.php');

        await page.locator('#apbl_author_position').fill(position);
        await page.locator('#apbl_member_since_label').fill(memberSince);
        await page.locator('#apbl_social_facebook').fill(facebook);

        await page.locator('#submit').click();

        await expect(page.locator('.notice-success, #message.updated')).toBeVisible();

        // Reload and verify the values stuck.
        await page.goto('/wp-admin/profile.php');
        await expect(page.locator('#apbl_author_position')).toHaveValue(position);
        await expect(page.locator('#apbl_member_since_label')).toHaveValue(memberSince);
        await expect(page.locator('#apbl_social_facebook')).toHaveValue(facebook);
    });
});
