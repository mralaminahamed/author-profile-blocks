---
layout: default
title: Troubleshooting
nav_order: 7
permalink: /troubleshooting/
---

# Troubleshooting
{: .no_toc }

Solutions for common issues with the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Installation Issues

### Plugin Won't Activate

If the plugin won't activate, check the following:

1. **WordPress Version**: Ensure you're running WordPress 6.0 or higher.
2. **PHP Version**: Verify your server is running PHP 7.4 or higher.
3. **Error Messages**: Check for any error messages during activation.
4. **Plugin Conflicts**: Temporarily deactivate all other plugins to check for conflicts.
5. **Memory Limit**: Increase the WordPress memory limit in your wp-config.php file:

```php
define( 'WP_MEMORY_LIMIT', '256M' );
```

### Updates Not Appearing

If plugin updates aren't appearing:

1. **Refresh Updates**: Go to Dashboard > Updates and click "Check Again"
2. **Clear Cache**: Clear your browser cache and any server-side caching
3. **WordPress Core**: Ensure WordPress core is up to date
4. **Transients**: Clear WordPress transients using a plugin like WP-Optimize

## Block Editor Issues

### Blocks Not Appearing

If Author Profile Blocks don't appear in the block inserter:

1. **Plugin Activation**: Confirm the plugin is correctly activated
2. **Block Editor**: Ensure you're using the block editor (Gutenberg), not the classic editor
3. **Cache**: Clear your browser cache and any caching plugins
4. **Script Loading**: Check your browser console for JavaScript errors
5. **Plugin Conflicts**: Try disabling other block-related plugins to check for conflicts

### Block Editor Crashes

If the block editor crashes when using Author Profile Blocks:

1. **Browser Console**: Check for JavaScript errors in your browser console
2. **Memory Limits**: Increase PHP memory limits and WordPress memory limits
3. **Author Count**: Reduce the number of authors if you're trying to display many at once
4. **Block Corruption**: Try re-adding the block from scratch rather than editing an existing one
5. **Safe Mode**: Access the editor in recovery mode: `example.com/wp-admin/post.php?post=123&action=edit&recovery-mode=1`

### Cannot Save Post with Blocks

If you can't save a post containing Author Profile Blocks:

1. **Error Messages**: Note any error messages that appear when saving
2. **Block Validation**: Check if there are block validation errors (red exclamation marks)
3. **Autosave Data**: Look for an autosave recovery option
4. **Post Size**: Reduce the number of blocks on the page (some servers limit post size)
5. **Browser Extensions**: Disable browser extensions that might interfere with the editor

## Display Issues

### Author Images Not Showing

If author profile images aren't displaying:

1. **Gravatar Setup**: Ensure authors have set up their profile images on [Gravatar](https://gravatar.com)
2. **Email Addresses**: Verify that user email addresses match their Gravatar accounts
3. **Display Setting**: Check that "Show Author Image" is enabled in the block settings
4. **Image Loading**: Inspect the page to see if image URLs are correct but failing to load
5. **Default Image**: Consider adding a default profile image fallback:

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    if ( empty( $author_data['image'] ) || strpos( $author_data['image'], 'gravatar.com/avatar/00000' ) !== false ) {
        $author_data['image'] = get_stylesheet_directory_uri() . '/images/default-avatar.png';
    }
    return $author_data;
}, 10, 2 );
```

### Carousel Not Working

If the Author Carousel isn't functioning properly:

1. **jQuery**: Ensure jQuery is loaded on your site (it's required for the carousel)
2. **Console Errors**: Check your browser console for JavaScript errors
3. **Script Loading**: Verify that slick-carousel.min.js is loading properly
4. **Multiple Carousels**: If using multiple carousels, ensure they have unique IDs
5. **Plugin Conflicts**: Disable other slider/carousel plugins to check for conflicts

### Responsive Design Problems

If blocks don't look right on mobile or tablet devices:

1. **Theme Compatibility**: Check if your theme has responsive styling conflicts
2. **Column Settings**: Adjust the number of columns in the Grid block
3. **Slides to Show**: Reduce the number of slides to show in the Carousel block
4. **Content Length**: Keep author descriptions brief for better mobile display
5. **Custom CSS**: Add responsive adjustments with custom CSS:

```css
@media (max-width: 768px) {
    .apb-author-description {
        display: none; /* Hide descriptions on mobile */
    }
    .apb-author-image img {
        max-width: 80px; /* Smaller images on mobile */
    }
}
```

### Styling Conflicts with Theme

If the blocks don't match your theme's styling:

1. **Block Settings**: Use the block settings to adjust colors and styling
2. **Theme Integration**: Add CSS to match your theme's design (see [Customization]({{ site.baseurl }}{% link customization.md %}))
3. **Inspector Tools**: Use browser inspector tools to identify conflicting styles
4. **Theme Support**: Check if your theme has special requirements for block styling
5. **Regenerate Assets**: If your theme uses a CSS optimizer, regenerate the assets

## Data Issues

### Authors Not Appearing

If selected authors don't appear in the blocks:

1. **User Roles**: Check if the authors have the correct user roles
2. **Author Filter**: Verify if "Filter by Role" is restricting the display
3. **Maximum Authors**: Check if the "Maximum Authors" setting is limiting the display
4. **User Status**: Ensure the user accounts are active and not deleted
5. **Cache**: Clear any caching plugins that might be serving old content

### Author Data Not Updating

If changes to author profiles aren't reflected in the blocks:

1. **Cache**: Clear all caching plugins and browser cache
2. **Block Cache**: The plugin caches author data; deactivate and reactivate the plugin to clear it
3. **Preview vs. Published**: Check if the changes appear in preview but not in published content
4. **User Meta**: Verify that user meta fields are being saved correctly
5. **Developer Tools**: For developers, enable development mode:

```php
add_filter( 'author_profile_blocks_development_mode', '__return_true' );
```

### Social Media Links Not Working

If social media links aren't working correctly:

1. **URL Format**: Ensure URLs include the https:// prefix
2. **Valid URLs**: Check that the URLs are valid and accessible
3. **Display Setting**: Verify that "Show Social Links" is enabled in the block settings
4. **Missing Icons**: If icons are missing, check for CSS conflicts
5. **Click Events**: Use browser inspector to see if click events are being intercepted

## Performance Issues

### Slow Page Loading

If pages with Author Profile Blocks load slowly:

1. **Number of Authors**: Reduce the number of authors displayed
2. **Image Optimization**: Ensure author images are properly sized and optimized
3. **Lazy Loading**: The plugin uses lazy loading for images; ensure it's not conflicting with other lazy load plugins
4. **Caching**: Implement a caching solution for your WordPress site
5. **Query Optimization**: For developers, optimize the author queries:

```php
add_filter( 'author_profile_blocks_author_query_args', function( $query_args ) {
    // Only request needed fields
    $query_args['fields'] = array( 'ID', 'display_name', 'user_email', 'user_registered' );
    return $query_args;
}, 10 );
```

### High Server Resource Usage

If the plugin is causing high server resource usage:

1. **Author Limit**: Set a lower "Maximum Authors" value
2. **Block Usage**: Limit the number of blocks on a single page
3. **Query Monitoring**: Use a query monitor plugin to identify problematic queries
4. **Caching Strategy**: Implement object caching (Redis or Memcached)
5. **Image Sizes**: Optimize image delivery with proper image sizes

### Block Editor Slowdown

If the block editor becomes slow when using multiple blocks:

1. **Author Selection**: Limit the number of authors in each block
2. **Display Options**: Disable unused display options
3. **Blocks per Page**: Reduce the total number of blocks on the page
4. **Browser Resources**: Check browser CPU and memory usage
5. **Editor Performance**: Try the site health tool for performance recommendations

## Integration Issues

### Conflicts with Other Plugins

If Author Profile Blocks conflicts with other plugins:

1. **Isolate Conflicts**: Deactivate plugins one by one to identify the conflict
2. **Script Conflicts**: Check for JavaScript errors in the browser console
3. **jQuery Issues**: Some plugins modify jQuery behavior, which can affect the carousel
4. **Block Editor Plugins**: Pay special attention to other block editor enhancement plugins
5. **REST API Plugins**: Check plugins that modify the WordPress REST API

### Theme Compatibility

If the plugin isn't compatible with your theme:

1. **Block Support**: Ensure your theme properly supports the block editor
2. **CSS Reset**: Some themes use aggressive CSS resets that affect the blocks
3. **Script Handling**: Check if the theme is properly enqueuing WordPress scripts
4. **Template Structure**: Verify the theme's template structure isn't interfering with blocks
5. **Support**: Contact your theme developer for support with block editor compatibility

### Multilingual Sites

If using the plugin on multilingual sites:

1. **Translation Plugin**: Ensure compatibility with your translation plugin (WPML, Polylang, etc.)
2. **String Translation**: Register dynamic strings for translation
3. **Author Language**: Consider how author information will be displayed across languages
4. **RTL Support**: Test the blocks in RTL languages if applicable
5. **Translation-Ready**: Ensure all text strings are properly wrapped for translation

## Advanced Troubleshooting

### Debugging

For developers, enable debugging to help identify issues:

1. **WordPress Debug Mode**: Enable WordPress debug mode in wp-config.php:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

2. **Query Monitor**: Install the Query Monitor plugin to analyze queries and performance
3. **Browser Developer Tools**: Use browser developer tools to inspect elements and monitor network requests
4. **Script Debugging**: Enable script debugging in wp-config.php:

```php
define( 'SCRIPT_DEBUG', true );
```

5. **Plugin Development Mode**: Enable the plugin's development mode:

```php
add_filter( 'author_profile_blocks_development_mode', '__return_true' );
```

### Common Error Messages

Here are solutions for common error messages:

#### "Call to undefined function authorProfileBlocks()"

This error occurs if the plugin isn't properly initialized. Deactivate and reactivate the plugin.

#### "Cannot read property 'slick' of undefined"

This error indicates that jQuery or the Slick Carousel script isn't loading properly. Check for JavaScript conflicts or missing dependencies.

#### "REST API Error: Author data not available"

This error shows when the plugin can't retrieve author data via the REST API. Check REST API permissions and authentication.

#### "Block validation: Block validation failed"

This error appears when the saved block structure doesn't match the expected structure. Try re-adding the block from scratch.

### Creating a Support Request

If you need to contact support, provide the following information:

1. **WordPress Version**: Your WordPress version
2. **Plugin Version**: The Author Profile Blocks version
3. **Theme**: Your active theme and version
4. **Other Plugins**: List of other active plugins
5. **Error Messages**: Any error messages you're seeing
6. **Steps to Reproduce**: Detailed steps to reproduce the issue
7. **Screenshots**: Visual evidence of the problem
8. **Server Environment**: PHP version, memory limits, etc.

## Getting More Help

If you're still experiencing issues, you can get additional help from:

- [WordPress.org Support Forums](https://wordpress.org/support/plugin/author-profile-blocks/)
- [GitHub Issues](https://github.com/mralaminahamed/author-profile-blocks/issues)
- [Plugin Documentation]({{ site.baseurl }}{% link index.md %})