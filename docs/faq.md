---
layout: default
title: FAQ
nav_order: 6
permalink: /faq/
---

# Frequently Asked Questions
{: .no_toc }

Common questions and answers about the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## General Questions

### Does this plugin create a custom post type?

No. Author Profile Blocks uses your existing WordPress users instead of creating a separate custom post type. This prevents duplicate content and leverages the built-in user management system.

### What WordPress versions are supported?

Author Profile Blocks requires WordPress 6.0 or higher. We recommend using the latest version of WordPress for the best experience and compatibility.

### What PHP versions are supported?

The plugin requires PHP 7.4 or higher. For optimal performance and security, we recommend using PHP 8.0 or higher.

### Does the plugin work with custom themes?

Yes, Author Profile Blocks is designed to work with any WordPress theme that supports the block editor (Gutenberg). The plugin includes its own styling to ensure compatibility across themes.

### Does it work with Elementor, Divi, or other page builders?

Author Profile Blocks is primarily designed for the WordPress block editor (Gutenberg). While the blocks themselves won't work directly in other page builders, you can:

1. Use a "Gutenberg Block" element in page builders that support it
2. Create template parts with the blocks and include them in your page builder layouts

### Is the plugin translation-ready?

Yes, Author Profile Blocks is fully internationalized and translation-ready. All text strings can be translated using standard WordPress translation tools.

## User Profiles

### How do I add author information?

The plugin adds extra fields to the standard WordPress user profile. You can edit any user and add their position/title, extended description, social media links, and more. See the [User Profiles]({{ site.baseurl }}{% link user-profiles.md %}) page for detailed instructions.

### Where do author images come from?

Author profile pictures are pulled from WordPress Gravatar using the author's email address. To set or update an author's image, they need to register that email at [Gravatar.com](https://gravatar.com).

### Can I use custom profile images instead of Gravatar?

The plugin currently uses Gravatar for profile images. However, developers can use the `author_profile_blocks_author_data` filter to modify the image source:

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    // Use ACF image field instead of Gravatar
    $image_id = get_field( 'profile_image', 'user_' . $user->ID );
    if ( $image_id ) {
        $image_url = wp_get_attachment_image_url( $image_id, 'medium' );
        if ( $image_url ) {
            $author_data['image'] = $image_url;
        }
    }
    return $author_data;
}, 10, 2 );
```

### How many authors can I display?

There is no hard limit on the number of authors you can display. However, for performance reasons, we recommend:

- **Author Grid**: 20-30 authors maximum
- **Author Carousel**: 10-15 authors maximum
- **Author List**: 30-40 authors maximum

For sites with many authors, use the "Maximum Authors" setting and pagination techniques.

## Block Usage

### Can I customize how the author profiles appear?

Yes! Each block includes many customization options in the block sidebar:

- Choose from multiple layouts (card, compact, centered, etc.)
- Toggle visibility of image, email, description, social links, etc.
- Change background and border colors
- Add shadows and rounded corners
- Adjust text alignment and spacing
- And much more!

For advanced customization, you can add custom CSS. See the [Customization]({{ site.baseurl }}{% link customization.md %}) page for details.

### Can I filter which authors are displayed?

Yes. You can:

1. Select specific authors to display
2. Filter by user role (Administrator, Editor, Author, Contributor)
3. Limit the maximum number of displayed authors

### How do social media profiles work?

The plugin adds fields for Facebook, Twitter, LinkedIn, Instagram, and personal website URLs to each user profile. When enabled in the blocks, these appear as social icons that visitors can click to view the author's profiles.

### Is the plugin responsive?

Yes, all blocks are designed to look great on all screen sizes from mobile devices to desktop computers. Layouts automatically adjust based on the available screen space:

- **Grid Block**: Reduces columns on smaller screens
- **Carousel Block**: Shows fewer slides on smaller screens
- **List Block**: Adjusts spacing and alignment for mobile devices

### Can I reuse the same author selection across multiple blocks?

Yes! Here are two approaches:

1. **Using Block Patterns**: Create and save block patterns containing your author selection
2. **Using Reusable Blocks**: Convert your configured blocks to reusable blocks

### How do I display authors in a particular order?

The order of authors in Grid, Carousel, and List blocks is determined by the order in which you select them in the block editor. You can reorder authors by removing and re-adding them in the desired order.

For developers, you can also modify the order using the `author_profile_blocks_authors` filter:

```php
add_filter( 'author_profile_blocks_authors', function( $authors, $query_args ) {
    // Sort authors alphabetically by name
    usort( $authors, function( $a, $b ) {
        return strcmp( $a['title'], $b['title'] );
    } );
    return $authors;
}, 10, 2 );
```

## Troubleshooting

### Why aren't my author images showing up?

If author images aren't displaying:

1. Make sure the authors have valid email addresses associated with their WordPress accounts
2. Verify that those email addresses have been registered at [Gravatar.com](https://gravatar.com)
3. Check that the "Show Author Image" option is enabled in the block settings
4. Clear your browser cache and any server-side caching

### The carousel navigation doesn't work

If the carousel navigation (arrows or dots) isn't working:

1. Make sure jQuery is loaded on your site
2. Check for JavaScript console errors that might indicate conflicts with other plugins
3. Try disabling other plugins that might be interfering with jQuery or the Slick Carousel
4. Ensure your theme isn't removing or modifying default WordPress scripts

### How do I fix styling conflicts with my theme?

If you're experiencing styling conflicts:

1. Use the block settings to adjust appearance (background color, text alignment, etc.)
2. Add custom CSS targeting the specific block elements (see [Customization]({{ site.baseurl }}{% link customization.md %}))
3. Check if your theme has custom block styles that might be overriding the plugin styles

### Block editor is slow when using many author blocks

If you notice performance issues in the block editor:

1. Limit the number of author blocks on a single page
2. Use the "Maximum Authors" setting to reduce the number of authors displayed
3. Disable unused elements (description, social links, etc.)
4. Consider using "Reduced motion" mode in your operating system if animations are sluggish

## Advanced Usage

### Can I display posts written by the author?

The plugin focuses on displaying author information rather than associated posts. However, developers can extend the "More Content" section of the Author Profile block to include recent posts:

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    // Get recent posts by this author
    $recent_posts = get_posts( array(
        'author' => $user->ID,
        'posts_per_page' => 3,
        'post_status' => 'publish',
    ) );
    
    if ( ! empty( $recent_posts ) ) {
        $posts_html = '<div class="apb-author-recent-posts">';
        $posts_html .= '<h4>Recent Posts</h4>';
        $posts_html .= '<ul>';
        
        foreach ( $recent_posts as $post ) {
            $posts_html .= '<li><a href="' . get_permalink( $post ) . '">' . get_the_title( $post ) . '</a></li>';
        }
        
        $posts_html .= '</ul></div>';
        
        // Add to description or create a custom field
        $author_data['recent_posts_html'] = $posts_html;
    }
    
    return $author_data;
}, 10, 2 );
```

### Can I integrate with membership or user role plugins?

Yes, Author Profile Blocks works with most membership and user role plugins. You can filter authors by role in the block settings, or use custom filters to integrate with specific plugins:

```php
add_filter( 'author_profile_blocks_authors', function( $authors, $query_args ) {
    // Example: Integration with Members plugin
    foreach ( $authors as $key => $author ) {
        // Check if user has a specific capability from a membership plugin
        $user = get_user_by( 'id', $author['id'] );
        if ( ! user_can( $user, 'access_premium_content' ) ) {
            // Remove authors without the capability
            unset( $authors[ $key ] );
        }
    }
    return array_values( $authors ); // Re-index array
}, 10, 2 );
```

### How do I add custom fields to author profiles?

See the [User Profiles]({{ site.baseurl }}{% link user-profiles.md %}) page for instructions on adding custom fields to the author profile section.

## Getting Help

### Where can I report bugs or request features?

You can report bugs or request features on our [GitHub repository](https://github.com/mralaminahamed/author-profile-blocks/issues).

### How do I contact support?

For support questions, please visit the [WordPress.org support forum](https://wordpress.org/support/plugin/author-profile-blocks/) for the plugin.

### Can I contribute to the plugin?

Yes! Contributions are welcome. Check out our [GitHub repository](https://github.com/mralaminahamed/author-profile-blocks) and read the [Contributing]({{ site.baseurl }}{% link contributing.md %}) guidelines for more information.