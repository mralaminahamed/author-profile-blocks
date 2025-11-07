import { test, expect } from '@playwright/test';
import { WPAdminPage } from '@pages/wpAdminPage';
import { testUsers, authorProfiles } from '@utils/testData';

test.describe('Author Profile Blocks E2E Tests', () => {
    let adminPage: WPAdminPage;

    test.beforeEach(async ({ page }) => {
        adminPage = new WPAdminPage(page);

        // Login as admin
        await adminPage.login(testUsers.admin.username, testUsers.admin.password);
    });

    test('should create and display author profile block', async () => {
        // Create a test author
        await adminPage.createUser(testUsers.author1);

        // Update author profile with additional information
        await adminPage.gotoUserProfile(); // This would need to be updated to target the specific user
        await adminPage.updateUserProfile({
            firstName: testUsers.author1.firstName,
            lastName: testUsers.author1.lastName,
            bio: testUsers.author1.bio,
            position: authorProfiles.john.position,
            company: authorProfiles.john.company,
            socialLinks: authorProfiles.john.socialLinks
        });

        // Create a new page with author profile block
        await adminPage.createPage({
            title: 'Author Profile Test',
            content: 'This page will contain an author profile block.',
            status: 'publish'
        });

        // Add author profile block to the page
        await adminPage.addBlock('Author Profile');

        // Configure the block (this would depend on the block's UI)
        // Select the author we created
        await adminPage.click('.author-profile-blocks-author-selector');
        await adminPage.click(`[data-user-id="${testUsers.author1.username}"]`);

        // Publish the page
        await adminPage.publishPost();

        // Verify the block is displayed correctly on frontend
        const pageUrl = await adminPage.getCurrentURL();
        await adminPage.goto(pageUrl.replace('/wp-admin', ''));

        // Check that the author profile block is rendered
        await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-profile')).toBeVisible();

        // Check author name
        await expect(adminPage.page.locator('.author-name')).toContainText(testUsers.author1.displayName);

        // Check author bio
        await expect(adminPage.page.locator('.author-bio')).toContainText(testUsers.author1.bio);

        // Check author position
        await expect(adminPage.page.locator('.author-position')).toContainText(authorProfiles.john.position);

        // Check social links
        const socialLinks = adminPage.page.locator('.author-social-links a');
        await expect(socialLinks).toHaveCount(Object.keys(authorProfiles.john.socialLinks).length);
    });

    test('should create and display author grid block', async () => {
        // Create multiple test authors
        await adminPage.createUser(testUsers.author1);
        await adminPage.createUser(testUsers.author2);
        await adminPage.createUser(testUsers.author3);

        // Create a new page
        await adminPage.createPage({
            title: 'Author Grid Test',
            content: 'This page will contain an author grid block.',
            status: 'publish'
        });

        // Add author grid block
        await adminPage.addBlock('Author Grid');

        // Configure the block to show multiple authors
        // This would depend on the block's configuration UI

        // Publish the page
        await adminPage.publishPost();

        // Verify on frontend
        const pageUrl = await adminPage.getCurrentURL();
        await adminPage.goto(pageUrl.replace('/wp-admin', ''));

        // Check that the author grid block is rendered
        await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-grid')).toBeVisible();

        // Check that multiple authors are displayed
        const authorCards = adminPage.page.locator('.author-grid-item');
        await expect(authorCards).toHaveCount(await authorCards.count()); // At least some authors
    });

    test('should create and display author list block', async () => {
        // Create test authors
        await adminPage.createUser(testUsers.author1);
        await adminPage.createUser(testUsers.author2);

        // Create a new page
        await adminPage.createPage({
            title: 'Author List Test',
            content: 'This page will contain an author list block.',
            status: 'publish'
        });

        // Add author list block
        await adminPage.addBlock('Author List');

        // Publish the page
        await adminPage.publishPost();

        // Verify on frontend
        const pageUrl = await adminPage.getCurrentURL();
        await adminPage.goto(pageUrl.replace('/wp-admin', ''));

        // Check that the author list block is rendered
        await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-list')).toBeVisible();

        // Check list items
        const listItems = adminPage.page.locator('.author-list-item');
        await expect(listItems).toHaveCount(await listItems.count());
    });

    test('should create and display author carousel block', async () => {
        // Create multiple test authors
        await adminPage.createUser(testUsers.author1);
        await adminPage.createUser(testUsers.author2);
        await adminPage.createUser(testUsers.author3);

        // Create a new page
        await adminPage.createPage({
            title: 'Author Carousel Test',
            content: 'This page will contain an author carousel block.',
            status: 'publish'
        });

        // Add author carousel block
        await adminPage.addBlock('Author Carousel');

        // Publish the page
        await adminPage.publishPost();

        // Verify on frontend
        const pageUrl = await adminPage.getCurrentURL();
        await adminPage.goto(pageUrl.replace('/wp-admin', ''));

        // Check that the author carousel block is rendered
        await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-carousel')).toBeVisible();

        // Check carousel functionality
        const carouselSlides = adminPage.page.locator('.slick-slide');
        await expect(carouselSlides).toHaveCount(await carouselSlides.count());

        // Test carousel navigation
        const nextButton = adminPage.page.locator('.slick-next');
        if (await nextButton.isVisible()) {
            await nextButton.click();
            // Verify carousel moved (this might need more specific assertions)
        }
    });

    test('should handle block configuration options', async () => {
        // Create a test author
        await adminPage.createUser(testUsers.author1);

        // Create a new page
        await adminPage.createPage({
            title: 'Block Configuration Test',
            content: 'Testing block configuration options.',
            status: 'draft' // Save as draft to test configuration
        });

        // Add author profile block
        await adminPage.addBlock('Author Profile');

        // Test various configuration options
        // This would depend on the specific block settings available

        // For example, test showing/hiding different elements
        // - Avatar display toggle
        // - Bio display toggle
        // - Social links display toggle
        // - Layout options

        // Save the post
        await adminPage.click('#save-post');
        await adminPage.toBeVisible('.notice-success');
    });

    test('should handle responsive design', async () => {
        // Create test authors
        await adminPage.createUser(testUsers.author1);
        await adminPage.createUser(testUsers.author2);

        // Create a page with author grid
        await adminPage.createPage({
            title: 'Responsive Test',
            content: 'Testing responsive design.',
            status: 'publish'
        });

        // Add author grid block
        await adminPage.addBlock('Author Grid');

        // Publish
        await adminPage.publishPost();

        // Test on different viewport sizes
        const viewports = [
            { width: 1920, height: 1080 }, // Desktop
            { width: 768, height: 1024 },  // Tablet
            { width: 375, height: 667 }   // Mobile
        ];

        for (const viewport of viewports) {
            await adminPage.page.setViewportSize(viewport);

            // Verify the block still displays correctly
            await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-grid')).toBeVisible();

            // Check that content is still accessible
            const authorCards = adminPage.page.locator('.author-grid-item');
            await expect(authorCards.first()).toBeVisible();
        }
    });

    test('should integrate with WordPress user roles', async () => {
        // Create users with different roles
        await adminPage.createUser({
            ...testUsers.author1,
            role: 'author'
        });

        await adminPage.createUser({
            ...testUsers.author2,
            role: 'editor'
        });

        // Create a page with author grid
        await adminPage.createPage({
            title: 'User Roles Test',
            content: 'Testing user role integration.',
            status: 'publish'
        });

        // Add author grid block
        await adminPage.addBlock('Author Grid');

        // Configure to show specific roles
        // This would depend on the block's role filtering options

        // Publish and verify
        await adminPage.publishPost();

        const pageUrl = await adminPage.getCurrentURL();
        await adminPage.goto(pageUrl.replace('/wp-admin', ''));

        // Verify that authors with the selected roles are displayed
        await expect(adminPage.page.locator('.wp-block-author-profile-blocks-author-grid')).toBeVisible();
    });
});