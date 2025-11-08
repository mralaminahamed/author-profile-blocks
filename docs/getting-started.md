---
layout: default
title: Getting Started
nav_order: 2
permalink: /getting-started/
---

# Getting Started with Author Profile Blocks

{: .no_toc }

This guide will help you install, configure, and start using the Author Profile Blocks plugin on your WordPress site.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Requirements

Before installing Author Profile Blocks, ensure your WordPress environment meets these minimum requirements:

### System Requirements

- **WordPress**: 6.0 or higher (recommended: latest version)
- **PHP**: 7.4 or higher (recommended: 8.0+ for optimal performance)
- **MySQL/MariaDB**: 5.6 or higher
- **Memory**: At least 128MB PHP memory limit (256MB recommended)

### Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile
- **Block Editor**: Full support for WordPress Gutenberg editor

### Theme Compatibility

- **Block Editor Support**: Your theme must support the WordPress block editor (Gutenberg)
- **Most modern themes** (2020+) include block editor support by default
- **Classic themes** may need updates or the [Gutenberg plugin](https://wordpress.org/plugins/gutenberg/)

{: .callout-warning }
**Note**: If your theme doesn't support the block editor, you can still use the plugin's blocks by installing the Gutenberg plugin or switching to a block-compatible theme.

## Installation

### Method 1: Automatic Installation (Recommended)

1. Log in to your WordPress dashboard
2. Navigate to **Plugins > Add New**
3. In the search field, type "Author Profile Blocks"
4. Click "Search Plugins"
5. Find "Author Profile Blocks" by Al Amin Ahamed
6. Click "Install Now"
7. After installation completes, click "Activate"

### Method 2: Manual Installation

1. Download the plugin ZIP file from:
    - [WordPress.org Plugin Directory](https://wordpress.org/plugins/author-profile-blocks/)
    - [GitHub Releases](https://github.com/mralaminahamed/author-profile-blocks/releases)
2. Log in to your WordPress dashboard
3. Navigate to **Plugins > Add New**
4. Click the "Upload Plugin" button at the top of the page
5. Choose the downloaded ZIP file
6. Click "Install Now"
7. After installation completes, click "Activate"

### Method 3: Composer Installation (For Developers)

If you're using Composer for WordPress development:

```bash
composer require mralaminahamed/author-profile-blocks
```

Then activate the plugin through the WordPress admin or WP-CLI:

```bash
wp plugin activate author-profile-blocks
```

### Installation Verification

After activation, verify the installation:

1. Go to **Plugins > Installed Plugins**
2. Look for "Author Profile Blocks" in the list
3. Ensure it shows "Active" status
4. Check for any error messages or notices

{: .callout-success }
**Success Check**: You should now see four new blocks available in the block editor: Author Profile, Author Grid, Author Carousel, and Author List.

## Initial Setup

### Setting Up Author Profiles

The plugin works with your existing WordPress users, but you can enhance their profiles with additional information for richer displays.

#### Accessing User Profiles

1. Log in to your WordPress dashboard
2. Navigate to **Users > All Users**
3. Click on a user to edit their profile, or click "Add New" to create a new user

#### Author Profile Information Section

After activation, you'll see a new "Author Profile Information" section at the bottom of the user profile page. This section includes:

- **Position/Title**: Job title, role, or position (e.g., "Senior Developer", "Marketing Manager")
- **Author Description**: Extended biographical information with rich text editor support
- **Social Media Profiles**: Links to Facebook, Twitter, LinkedIn, Instagram, and personal website
- **Member Since Label**: Customizable text for the registration date display (default: "Member since")

#### Filling Out Profile Information

1. Scroll down to the "Author Profile Information" section
2. Fill in the desired fields:
    - **Position/Title**: Enter the person's job title or role
    - **Author Description**: Write a detailed bio (supports formatting, links, etc.)
    - **Social Media**: Add full URLs including https:// (e.g., https://twitter.com/username)
    - **Member Since Label**: Customize the date label (e.g., "Joined", "With us since")
3. Click "Update User" or "Update Profile" to save changes

{: .callout-tip }
**Pro Tip**: For best results, add profile pictures via Gravatar using the user's email address, or use custom profile images if your theme supports them.

#### Bulk Profile Management

For sites with many authors, consider using a user management plugin or the plugin's developer hooks for bulk updates.

### Adding Blocks to Your Content

Once the plugin is activated, you can start using the blocks in your content.

#### Creating or Editing Content

1. Go to **Pages > Add New** or **Posts > Add New** in your WordPress dashboard
2. Alternatively, edit an existing page or post that supports the block editor

#### Inserting Author Blocks

1. Click the **+** button to open the block inserter
2. Search for "author" in the search field
3. You'll see four Author Profile Blocks available:
    - **Author Profile**: Single author display
    - **Author Grid**: Multiple authors in a grid
    - **Author Carousel**: Multiple authors in a slideshow
    - **Author List**: Multiple authors in a list format
4. Click on the block you want to add

#### Basic Block Configuration

After adding a block:

1. **Select Authors**: Use the author picker to choose which users to display
2. **Configure Display Options**: Choose what information to show (image, name, position, etc.)
3. **Adjust Layout**: Select layout options (card, compact, centered, etc.)
4. **Style Settings**: Customize colors, spacing, and visual appearance
5. **Preview Changes**: Use the preview mode to see how the block will look

#### Publishing Your Content

1. Click **Save Draft** to save your work, or **Publish** to make it live
2. View the published page to see your author blocks in action

{: .callout-info }
**Block Editor Tips**:

- Use the **List View** (Ctrl/Cmd + O) to see all blocks on your page
- **Duplicate blocks** by selecting them and pressing Ctrl/Cmd + Shift + D
- **Move blocks** by dragging the block handle (⋮⋮) icon

## Quick Block Overview

Here's a brief overview of each block:

### Author Profile Block

Displays a single author with comprehensive information. Perfect for author bio pages, about pages, or contact information.

![Author Profile Block]({{ site.baseurl }}/assets/images/author-profile-block.png)

### Author Grid Block

Presents multiple authors in a responsive grid layout. Ideal for team pages, contributor listings, or showcasing multiple authors.

![Author Grid Block]({{ site.baseurl }}/assets/images/author-grid-block.png)

### Author Carousel Block

Showcases multiple authors in an interactive sliding carousel. Great for featuring team members or contributors in a space-efficient manner.

![Author Carousel Block]({{ site.baseurl }}/assets/images/author-carousel-block.png)

### Author List Block

Displays multiple authors in a customizable list format. Perfect for vertical author listings or team directories.

![Author List Block]({{ site.baseurl }}/assets/images/author-list-block.png)

## Testing Your Installation

### Quick Test

1. Create a new page or edit an existing one
2. Add an "Author Profile" block
3. Select yourself or another user from the author picker
4. Publish the page and view it
5. Verify that the author information displays correctly

### Troubleshooting Installation

If blocks don't appear or work correctly:

- **Check Plugin Status**: Ensure the plugin is activated in **Plugins > Installed Plugins**
- **Clear Caches**: Clear any caching plugins and browser cache
- **Check Console**: Open browser developer tools and check for JavaScript errors
- **Theme Compatibility**: Try switching to a default WordPress theme temporarily
- **Block Editor**: Ensure you're using the block editor, not the classic editor

### Common Issues

- **Blocks not showing**: Try refreshing the page or clearing browser cache
- **Author picker empty**: Ensure you have users with proper roles (Author, Editor, Administrator)
- **Styling issues**: Check if your theme has custom block editor styles

## Next Steps

Now that you have Author Profile Blocks up and running, explore these resources to get the most out of the plugin:

### Block-Specific Guides

- **[Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %})** - Detailed guide for single author displays
- **[Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %})** - Master grid layouts for team pages
- **[Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %})** - Create interactive author showcases
- **[Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %})** - Build comprehensive author directories

### Advanced Configuration

- **[User Profiles]({{ site.baseurl }}{% link user-profiles.md %})** - Complete guide to managing author information
- **[Customization]({{ site.baseurl }}{% link customization.md %})** - Advanced styling and theme integration
- **[FAQ]({{ site.baseurl }}{% link faq.md %})** - Answers to common questions
- **[Troubleshooting]({{ site.baseurl }}{% link troubleshooting.md %})** - Solutions for technical issues

### Developer Resources

- **[Developer API]({{ site.baseurl }}{% link developer-api.md %})** - Technical documentation for developers
- **[Advanced Examples]({{ site.baseurl }}{% link advanced-examples.md %})** - Integration examples and customizations
- **[Performance Guide]({{ site.baseurl }}{% link performance-guide.md %})** - Optimization strategies

### Community & Support

- **[GitHub Repository](https://github.com/mralaminahamed/author-profile-blocks)** - Report issues and contribute
- **[WordPress Support](https://wordpress.org/support/plugin/author-profile-blocks/)** - Get help from the community

{: .callout-success }
**Ready to build?** Start with the [Author Profile Block guide]({{ site.baseurl }}{% link blocks/author-profile.md %}) to create your first author display!
