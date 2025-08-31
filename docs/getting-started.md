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

Before installing Author Profile Blocks, ensure your WordPress environment meets these requirements:

- WordPress 6.0 or higher
- PHP 7.4 or higher
- A theme that supports the WordPress block editor (Gutenberg)

## Installation

### Automatic Installation

1. Log in to your WordPress dashboard and navigate to **Plugins > Add New**.
2. In the search field, type "Author Profile Blocks" and click "Search Plugins".
3. Once you've found the plugin, click "Install Now".
4. After installation, click "Activate" to start using the plugin.

### Manual Installation

1. Download the plugin ZIP file from [WordPress.org plugin page](https://wordpress.org/plugins/author-profile-blocks) or the [GitHub repository](https://github.com/mralaminahamed/author-profile-blocks/releases).
2. Log in to your WordPress dashboard and navigate to **Plugins > Add New**.
3. Click the "Upload Plugin" button at the top of the page.
4. Choose the ZIP file you downloaded and click "Install Now".
5. After installation, click "Activate" to start using the plugin.

## Initial Setup

### Setting Up Author Profiles

Before using the blocks, you may want to enhance the author profiles with additional information:

1. Go to **Users > All Users** in your WordPress dashboard.
2. Click on a user to edit their profile.
3. Scroll down to the "Author Profile Information" section.
4. Fill in the following fields:
   - Position/Title
   - Author Description
   - Social Media Profiles
   - Member Since Label (customizable text for the registration date)
5. Click "Update User" to save the changes.

![Author Profile Fields]({{ site.baseurl }}/assets/images/author-profile-fields.png)

### Adding Blocks to Your Content

Once the plugin is activated and you've set up author profiles, you can start adding the blocks to your content:

1. Create a new page or post, or edit an existing one.
2. Click the "+" button to add a new block.
3. Search for "Author" and you'll see the four available blocks:
   - Author Profile
   - Author Grid
   - Author Carousel
   - Author List
4. Select the block you want to add.
5. Configure the block settings in the sidebar.
6. Save or publish your page.

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

## Next Steps

Now that you have installed and set up the plugin, check out these pages to learn more:

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) - Learn how to customize the single author display
- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) - Discover options for grid layouts
- [Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %}) - Explore carousel settings and animations
- [Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %}) - Find out how to customize list layouts
- [User Profiles]({{ site.baseurl }}{% link user-profiles.md %}) - Learn more about managing author information
- [Customization]({{ site.baseurl }}{% link customization.md %}) - Discover styling options for all blocks