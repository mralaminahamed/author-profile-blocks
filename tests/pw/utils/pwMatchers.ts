/**
 * Custom Playwright matchers for Author Profile Blocks testing
 */
export const customExpect = {
    /**
     * Check if element contains author profile block
     */
    async toContainAuthorProfileBlock(received: any, authorName?: string) {
        const pass = await received.locator('.wp-block-author-profile-blocks-author-profile').count() > 0;

        if (pass) {
            if (authorName) {
                const authorLocator = received.locator('.wp-block-author-profile-blocks-author-profile');
                const hasAuthor = (await authorLocator.textContent())?.includes(authorName) ?? false;
                return {
                    message: () => `Expected element to contain author profile block with author "${authorName}"`,
                    pass: hasAuthor,
                };
            }
            return {
                message: () => 'Expected element to contain author profile block',
                pass: true,
            };
        }

        return {
            message: () => 'Expected element to contain author profile block',
            pass: false,
        };
    },

    /**
     * Check if element contains author grid block
     */
    async toContainAuthorGridBlock(received: any) {
        const pass = await received.locator('.wp-block-author-profile-blocks-author-grid').count() > 0;

        return {
            message: () => 'Expected element to contain author grid block',
            pass,
        };
    },

    /**
     * Check if element contains author list block
     */
    async toContainAuthorListBlock(received: any) {
        const pass = await received.locator('.wp-block-author-profile-blocks-author-list').count() > 0;

        return {
            message: () => 'Expected element to contain author list block',
            pass,
        };
    },

    /**
     * Check if element contains author carousel block
     */
    async toContainAuthorCarouselBlock(received: any) {
        const pass = await received.locator('.wp-block-author-profile-blocks-author-carousel').count() > 0;

        return {
            message: () => 'Expected element to contain author carousel block',
            pass,
        };
    },

    /**
     * Check if author profile has social links
     */
    async toHaveSocialLinks(received: any) {
        const socialLinks = received.locator('.author-social-links a');
        const count = await socialLinks.count();

        return {
            message: () => `Expected author profile to have social links, but found ${count}`,
            pass: count > 0,
        };
    },

    /**
     * Check if author profile has avatar
     */
    async toHaveAvatar(received: any) {
        const avatar = received.locator('.author-avatar img');
        const isVisible = await avatar.isVisible();

        return {
            message: () => 'Expected author profile to have avatar image',
            pass: isVisible,
        };
    },

    /**
     * Check if author profile has bio
     */
    async toHaveBio(received: any) {
        const bio = received.locator('.author-bio');
        const isVisible = await bio.isVisible();
        const hasContent = (await bio.textContent())?.trim().length > 0;

        return {
            message: () => 'Expected author profile to have bio content',
            pass: isVisible && hasContent,
        };
    },
};