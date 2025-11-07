import { Page } from '@playwright/test';
import { BasePage } from './basePage';

/**
 * WordPress Admin page object
 */
export class WPAdminPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Login to WordPress admin
     */
    async login(username: string = 'admin', password: string = 'password'): Promise<void> {
        await this.goto('/wp-login.php');
        await this.fill('#user_login', username);
        await this.fill('#user_pass', password);
        await this.click('#wp-submit');
        await this.toBeVisible('#wpadminbar');
    }

    /**
     * Navigate to admin page
     */
    async gotoAdmin(path: string = ''): Promise<void> {
        await this.goto(`/wp-admin/${path}`);
    }

    /**
     * Go to users page
     */
    async gotoUsers(): Promise<void> {
        await this.gotoAdmin('users.php');
    }

    /**
     * Go to user profile page
     */
    async gotoUserProfile(userId?: number): Promise<void> {
        const path = userId ? `user-edit.php?user_id=${userId}` : 'profile.php';
        await this.gotoAdmin(path);
    }

    /**
     * Go to posts page
     */
    async gotoPosts(): Promise<void> {
        await this.gotoAdmin('edit.php');
    }

    /**
     * Go to pages page
     */
    async gotoPages(): Promise<void> {
        await this.gotoAdmin('edit.php?post_type=page');
    }

    /**
     * Go to new post page
     */
    async gotoNewPost(): Promise<void> {
        await this.gotoAdmin('post-new.php');
    }

    /**
     * Go to new page
     */
    async gotoNewPage(): Promise<void> {
        await this.gotoAdmin('post-new.php?post_type=page');
    }

    /**
     * Go to plugins page
     */
    async gotoPlugins(): Promise<void> {
        await this.gotoAdmin('plugins.php');
    }

    /**
     * Create a new user
     */
    async createUser(userData: {
        username: string;
        email: string;
        firstName?: string;
        lastName?: string;
        role?: string;
    }): Promise<void> {
        await this.gotoAdmin('user-new.php');

        await this.fill('#user_login', userData.username);
        await this.fill('#email', userData.email);

        if (userData.firstName) {
            await this.fill('#first_name', userData.firstName);
        }

        if (userData.lastName) {
            await this.fill('#last_name', userData.lastName);
        }

        if (userData.role) {
            await this.selectOption('#role', userData.role);
        }

        await this.click('#createusersub');
        await this.toBeVisible('.notice-success');
    }

    /**
     * Update user profile
     */
    async updateUserProfile(profileData: {
        firstName?: string;
        lastName?: string;
        bio?: string;
        position?: string;
        company?: string;
        socialLinks?: { [key: string]: string };
    }): Promise<void> {
        if (profileData.firstName) {
            await this.fill('#first_name', profileData.firstName);
        }

        if (profileData.lastName) {
            await this.fill('#last_name', profileData.lastName);
        }

        if (profileData.bio) {
            await this.fill('#description', profileData.bio);
        }

        // Author Profile Blocks specific fields
        if (profileData.position) {
            await this.fill('#apbl_author_position', profileData.position);
        }

        if (profileData.company) {
            await this.fill('#apbl_author_company', profileData.company);
        }

        if (profileData.socialLinks) {
            for (const [platform, url] of Object.entries(profileData.socialLinks)) {
                await this.fill(`#apbl_social_${platform}`, url);
            }
        }

        await this.click('#submit');
        await this.toBeVisible('.notice-success');
    }

    /**
     * Create a new post
     */
    async createPost(postData: {
        title: string;
        content: string;
        status?: 'publish' | 'draft';
        author?: string;
    }): Promise<void> {
        await this.gotoNewPost();

        await this.fill('#title', postData.title);

        // Switch to code editor for content
        await this.click('#content-html');
        await this.fill('#content', postData.content);

        if (postData.status === 'publish') {
            await this.click('#publish');
        } else {
            await this.click('#save-post');
        }

        await this.toBeVisible('.notice-success');
    }

    /**
     * Create a new page
     */
    async createPage(pageData: {
        title: string;
        content: string;
        status?: 'publish' | 'draft';
    }): Promise<void> {
        await this.gotoNewPage();

        await this.fill('#title', pageData.title);

        // Switch to code editor for content
        await this.click('#content-html');
        await this.fill('#content', pageData.content);

        if (pageData.status === 'publish') {
            await this.click('#publish');
        } else {
            await this.click('#save-post');
        }

        await this.toBeVisible('.notice-success');
    }

    /**
     * Add a block to the editor
     */
    async addBlock(blockName: string): Promise<void> {
        // Click the add block button
        await this.click('.block-editor-inserter__toggle');

        // Search for the block
        await this.fill('.block-editor-inserter__search input', blockName);

        // Click on the block
        await this.click(`[data-title*="${blockName}"]`);
    }

    /**
     * Publish current post/page
     */
    async publishPost(): Promise<void> {
        await this.click('#publish');
        await this.toBeVisible('.notice-success');
    }

    /**
     * Logout from admin
     */
    async logout(): Promise<void> {
        await this.gotoAdmin();
        await this.click('#wp-admin-bar-logout a');
        await this.toBeVisible('#loginform');
    }
}