import { generateUserData } from './helpers';

/**
 * Test data for Author Profile Blocks E2E tests
 */

export const testUsers = {
    admin: {
        username: 'admin',
        password: 'password',
        email: 'admin@example.com'
    },
    author1: generateUserData({
        username: 'johndoe',
        email: 'john.doe@example.com',
        firstName: 'John',
        lastName: 'Doe',
        displayName: 'John Doe',
        bio: 'Senior WordPress Developer with 10+ years of experience in building custom themes and plugins.'
    }),
    author2: generateUserData({
        username: 'janesmith',
        email: 'jane.smith@example.com',
        firstName: 'Jane',
        lastName: 'Smith',
        displayName: 'Jane Smith',
        bio: 'UX Designer passionate about creating intuitive user experiences and beautiful interfaces.'
    }),
    author3: generateUserData({
        username: 'bobwilson',
        email: 'bob.wilson@example.com',
        firstName: 'Bob',
        lastName: 'Wilson',
        displayName: 'Bob Wilson',
        bio: 'DevOps Engineer specializing in cloud infrastructure and CI/CD pipelines.'
    })
};

export const authorProfiles = {
    john: {
        position: 'Senior Developer',
        company: 'Tech Solutions Inc.',
        location: 'San Francisco, CA',
        website: 'https://johndoe.dev',
        socialLinks: {
            linkedin: 'https://linkedin.com/in/johndoe',
            github: 'https://github.com/johndoe',
            twitter: 'https://twitter.com/johndoe'
        }
    },
    jane: {
        position: 'UX Designer',
        company: 'Design Studio',
        location: 'New York, NY',
        website: 'https://janesmith.design',
        socialLinks: {
            linkedin: 'https://linkedin.com/in/janesmith',
            dribbble: 'https://dribbble.com/janesmith',
            instagram: 'https://instagram.com/janesmith'
        }
    },
    bob: {
        position: 'DevOps Engineer',
        company: 'Cloud Systems',
        location: 'Austin, TX',
        website: 'https://bobwilson.dev',
        socialLinks: {
            linkedin: 'https://linkedin.com/in/bobwilson',
            github: 'https://github.com/bobwilson'
        }
    }
};

export const blockContent = {
    samplePost: {
        title: 'Getting Started with Author Profile Blocks',
        content: `Welcome to Author Profile Blocks! This powerful WordPress plugin allows you to showcase author profiles and team members using WordPress users.

## Features

- **Multiple Block Types**: Choose from Profile, Grid, List, and Carousel layouts
- **Social Media Integration**: Display social links and profiles
- **Customizable Design**: Extensive styling options and responsive layouts
- **User Management**: Easy author selection and management

## Getting Started

1. Install and activate the plugin
2. Add author information in user profiles
3. Insert blocks on your pages
4. Customize the appearance

Happy blogging!`
    },
    aboutPage: {
        title: 'About Our Team',
        content: `Meet the talented individuals behind our success. Our team is composed of experienced professionals dedicated to delivering exceptional results.

We believe in collaboration, innovation, and continuous learning. Each team member brings unique skills and perspectives to our projects.`
    }
};

export const testSelectors = {
    // Block editor selectors
    blockInserter: '[data-testid="block-inserter"]',
    blockSearch: '[data-testid="block-inserter-search"]',
    addBlockButton: '.block-editor-inserter__toggle',

    // Author Profile Block selectors
    authorProfileBlock: '.wp-block-author-profile-blocks-author-profile',
    authorSelector: '.author-profile-blocks-author-selector',
    authorCard: '.author-profile-card',
    authorAvatar: '.author-avatar img',
    authorName: '.author-name',
    authorBio: '.author-bio',
    authorSocialLinks: '.author-social-links',
    authorPosition: '.author-position',

    // Author Grid Block selectors
    authorGridBlock: '.wp-block-author-profile-blocks-author-grid',
    authorGridItem: '.author-grid-item',

    // Author List Block selectors
    authorListBlock: '.wp-block-author-profile-blocks-author-list',
    authorListItem: '.author-list-item',

    // Author Carousel Block selectors
    authorCarouselBlock: '.wp-block-author-profile-blocks-author-carousel',
    carouselSlide: '.slick-slide',
    carouselNext: '.slick-next',
    carouselPrev: '.slick-prev',

    // Admin selectors
    userProfilePage: '#your-profile',
    authorFields: '.author-profile-fields',
    socialLinksFields: '.social-links-fields',

    // Frontend selectors
    publishedPost: '.wp-block-post-content',
    authorInfo: '.author-info'
};