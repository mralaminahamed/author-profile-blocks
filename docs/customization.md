---
layout: default
title: Customization
nav_order: 5
permalink: /customization/
---

# Customization

{: .no_toc }

Learn how to customize the appearance and behavior of the Author Profile Blocks to match your website's design.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Using Block Settings

Each Author Profile Block includes comprehensive settings that allow you to customize its appearance directly in the block editor. These settings are accessible in the block sidebar when a block is selected.

### Common Style Options

All blocks include these common style options:

- **Background Color** - Set custom background colors for the block or individual items
- **Text Alignment** - Align text left, center, or right
- **Padding** - Adjust spacing around and within content
- **Borders** - Add, style, and colorize borders
- **Rounded Corners** - Add rounded corners to blocks or items
- **Shadow Effects** - Apply subtle shadow effects

![Style Options](../assets/images/style-options.png)

### Block-Specific Options

Each block type also has unique customization options:

- **Author Profile Block** - Layout options, "More Content" section
- **Author Grid Block** - Columns, item spacing, grid layout
- **Author Carousel Block** - Slides to show, autoplay, navigation options
- **Author List Block** - Display style, list format, dividers, hover effects

## Custom CSS

For more advanced customization, you can add custom CSS to your theme. Author Profile Blocks uses specific CSS classes that you can target.

### Global Classes

These classes apply to all blocks:

```css
/* Target all Author Profile Blocks */
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid,
.wp-block-author-profile-blocks-author-carousel,
.wp-block-author-profile-blocks-author-list {
	/* Your custom styles */
}

/* Target all author images */
.apb-author-image {
	/* Your custom styles */
}

/* Target all author names */
.apb-author-name {
	/* Your custom styles */
}

/* Target all author positions */
.apb-author-position {
	/* Your custom styles */
}

/* Target all author descriptions */
.apb-author-description {
	/* Your custom styles */
}

/* Target all social profiles */
.apb-social-profiles {
	/* Your custom styles */
}
```

### Block-Specific Classes

Each block type also has specific classes:

#### Author Profile Block

```css
/* Main wrapper */
.wp-block-author-profile-blocks-author-profile {
	/* Your custom styles */
}

/* More content section */
.apb-author-more-content {
	/* Your custom styles */
}

/* Layout-specific classes */
.apb-author-profile .is-layout-card {
	/* Your custom styles */
}
.apb-author-profile .is-layout-compact {
	/* Your custom styles */
}
.apb-author-profile .is-layout-centered {
	/* Your custom styles */
}
```

#### Author Grid Block

```css
/* Main wrapper */
.wp-block-author-profile-blocks-author-grid {
	/* Your custom styles */
}

/* Grid container */
.apb-author-grid {
	/* Your custom styles */
}

/* Individual grid items */
.apb-author-grid-item {
	/* Your custom styles */
}

/* Column-specific targeting */
.apb-columns-1 .apb-author-grid-item {
	/* Your custom styles for 1-column grid */
}
.apb-columns-2 .apb-author-grid-item {
	/* Your custom styles for 2-column grid */
}
.apb-columns-3 .apb-author-grid-item {
	/* Your custom styles for 3-column grid */
}
.apb-columns-4 .apb-author-grid-item {
	/* Your custom styles for 4-column grid */
}
```

#### Author Carousel Block

```css
/* Main wrapper */
.wp-block-author-profile-blocks-author-carousel {
	/* Your custom styles */
}

/* Carousel container */
.apb-author-carousel {
	/* Your custom styles */
}

/* Individual carousel slides */
.apb-author-carousel-slide {
	/* Your custom styles */
}

/* Carousel navigation */
.apb-author-carousel .slick-dots {
	/* Your custom styles for dots */
}
.apb-author-carousel .slick-arrow {
	/* Your custom styles for arrows */
}
```

#### Author List Block

```css
/* Main wrapper */
.wp-block-author-profile-blocks-author-list {
	/* Your custom styles */
}

/* List container */
.apb-author-list {
	/* Your custom styles */
}

/* Individual list items */
.apb-author-list-item {
	/* Your custom styles */
}

/* Style-specific targeting */
.apb-author-list.is-style-compact .apb-author-list-item {
	/* Your custom styles for compact list items */
}
.apb-author-list.is-style-detailed .apb-author-list-item {
	/* Your custom styles for detailed list items */
}
```

### Feature-Specific Classes

You can also target specific features across blocks:

```css
/* Items with shadows */
.has-shadow {
	/* Your custom styles */
}

/* Items with borders */
.has-border {
	/* Your custom styles */
}

/* Items with rounded corners */
.is-rounded {
	/* Your custom styles */
}

/* Items with hover effects */
.has-hover-effect {
	/* Your custom styles */
}
```

## Color Customization

### Using Theme Colors

Author Profile Blocks supports WordPress theme color palettes. To use your theme's colors:

1. Select a block
2. Open the color settings in the sidebar
3. Choose from your theme's color palette

![Theme Colors](../assets/images/theme-colors.png)

### Creating Color Schemes

You can create consistent color schemes by combining CSS variables with the block classes:

```css
/* Define color scheme */
:root {
	--apb-primary: #3498db;
	--apb-secondary: #2ecc71;
	--apb-text: #333333;
	--apb-background: #f8f9fa;
	--apb-accent: #e74c3c;
}

/* Apply to all blocks */
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid,
.wp-block-author-profile-blocks-author-carousel,
.wp-block-author-profile-blocks-author-list {
	color: var(--apb-text);
	background-color: var(--apb-background);
}

/* Style author names */
.apb-author-name {
	color: var(--apb-primary);
}

/* Style positions */
.apb-author-position {
	color: var(--apb-secondary);
}

/* Style social icons */
.apb-social-profiles a {
	color: var(--apb-accent);
}
```

## Image Customization

### Avatar Shape and Size

You can customize the appearance of author images:

```css
/* Make all author images circular */
.apb-author-image img {
	border-radius: 50%;
}

/* Add a border to images */
.apb-author-image img {
	border: 3px solid #3498db;
}

/* Add a hover effect to images */
.apb-author-image img {
	transition: transform 0.3s ease;
}
.apb-author-image img:hover {
	transform: scale(1.1);
}
```

### Custom Image Sizes

For developers, you can register custom image sizes for author avatars:

```php
/**
 * Register custom image sizes for author avatars
 */
function my_custom_avatar_sizes() {
    add_image_size( 'author-profile-large', 300, 300, true );
    add_image_size( 'author-profile-medium', 150, 150, true );
    add_image_size( 'author-profile-small', 80, 80, true );
}
add_action( 'after_setup_theme', 'my_custom_avatar_sizes' );
```

## Typography Customization

### Using Theme Typography

Author Profile Blocks inherits typography settings from your theme. However, you can customize specific text elements:

```css
/* Customize author names */
.apb-author-name {
	font-family: "Georgia", serif;
	font-weight: 700;
	font-size: 1.5rem;
	letter-spacing: 0.5px;
}

/* Customize author positions */
.apb-author-position {
	font-family: "Arial", sans-serif;
	font-style: italic;
	font-size: 0.9rem;
	text-transform: uppercase;
}

/* Customize author descriptions */
.apb-author-description {
	font-family: "Helvetica", sans-serif;
	font-size: 1rem;
	line-height: 1.6;
}
```

## Animation and Interaction

### Hover Effects

You can customize hover effects for all blocks:

```css
/* Card hover effect */
.apb-author-grid-item,
.apb-author-carousel-item,
.apb-author-list-item {
	transition: all 0.3s ease;
}
.apb-author-grid-item:hover,
.apb-author-carousel-item:hover,
.apb-author-list-item:hover {
	transform: translateY(-5px);
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Social icon hover effect */
.apb-social-profiles a {
	transition: color 0.2s ease;
}
.apb-social-profiles a:hover {
	color: #3498db;
}
```

### Carousel Animation

You can customize the carousel animation behavior:

```css
/* Fade transition between slides */
.apb-author-carousel .slick-slide {
	opacity: 0;
	transition: opacity 0.5s ease;
}
.apb-author-carousel .slick-slide.slick-active {
	opacity: 1;
}

/* Custom arrow styling */
.apb-author-carousel .slick-arrow {
	background-color: #3498db;
	border-radius: 50%;
	width: 40px;
	height: 40px;
}
.apb-author-carousel .slick-arrow:hover {
	background-color: #2980b9;
}
```

## Responsive Customization

Author Profile Blocks are responsive by default, but you can further customize their behavior on different screen sizes:

```css
/* Desktop styles (default) */
.apb-author-grid-item {
	padding: 30px;
}

/* Tablet styles */
@media (max-width: 768px) {
	.apb-author-grid-item {
		padding: 20px;
	}
	.apb-author-description {
		font-size: 0.9rem;
	}
}

/* Mobile styles */
@media (max-width: 480px) {
	.apb-author-grid-item {
		padding: 15px;
	}
	.apb-author-image img {
		width: 80px;
		height: 80px;
	}
	.apb-author-description {
		font-size: 0.8rem;
	}
}
```

## Creating Custom Templates

For developers, you can create custom templates for the blocks by filtering the rendered output:

```php
/**
 * Customize the rendered output of the Author Profile block
 *
 * @param string $block_content The rendered content.
 * @param array  $block        The block data.
 * @return string Modified content.
 */
function my_custom_author_profile_template( $block_content, $block ) {
    // Make modifications to the block content
    return $block_content;
}
add_filter( 'render_block_author-profile-blocks/author-profile', 'my_custom_author_profile_template', 10, 2 );
```

## Advanced: Filter Hooks

The plugin provides several filter hooks for developers to customize its behavior:

### Block-Related Filters

```php
// Modify block registration arguments
add_filter( 'author_profile_blocks_block_args', function( $args, $block_name ) {
    // Modify args for specific block
    if ( $block_name === 'author-profile' ) {
        // Your modifications
    }
    return $args;
}, 10, 2 );

// Filter rendered block content
add_filter( 'author_profile_blocks_rendered_block', function( $content, $block, $block_name ) {
    // Modify content for all blocks
    return $content;
}, 10, 3 );

// Filter rendered content for a specific block
add_filter( 'author_profile_blocks_rendered_author_profile', function( $content, $block ) {
    // Modify content for the Author Profile block only
    return $content;
}, 10, 2 );
```

### Author Data Filters

```php
// Modify author data before it's used in blocks
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    // Add or modify author data
    $author_data['custom_field'] = get_user_meta( $user->ID, 'custom_field', true );
    return $author_data;
}, 10, 2 );

// Filter authors query arguments
add_filter( 'author_profile_blocks_author_query_args', function( $query_args ) {
    // Modify query args
    $query_args['orderby'] = 'registered';
    $query_args['order'] = 'DESC';
    return $query_args;
}, 10 );

// Filter authors list after query
add_filter( 'author_profile_blocks_authors', function( $authors, $query_args ) {
    // Modify authors array
    return $authors;
}, 10, 2 );
```

## Real-World Customization Examples

### Corporate Team Page

Create a professional team page layout:

```css
/* Corporate team page styling */
.corporate-team .wp-block-author-profile-blocks-author-grid {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	padding: 60px 20px;
	border-radius: 20px;
	margin: 40px 0;
}

.corporate-team .apb-author-grid-item {
	background: white;
	border-radius: 15px;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
	transition: all 0.3s ease;
	overflow: hidden;
}

.corporate-team .apb-author-grid-item:hover {
	transform: translateY(-10px);
	box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.corporate-team .apb-author-image img {
	width: 120px;
	height: 120px;
	border-radius: 50%;
	border: 5px solid #667eea;
	margin: 30px auto 20px;
	display: block;
}

.corporate-team .apb-author-name {
	font-size: 1.4rem;
	font-weight: 700;
	color: #2c3e50;
	margin-bottom: 5px;
	text-align: center;
}

.corporate-team .apb-author-position {
	color: #667eea;
	font-weight: 600;
	text-align: center;
	margin-bottom: 15px;
}

.corporate-team .apb-author-description {
	text-align: center;
	color: #7f8c8d;
	font-size: 0.9rem;
	line-height: 1.5;
	padding: 0 20px 20px;
}
```

### Magazine Author Bios

Style for a magazine-style author bio section:

```css
/* Magazine-style author bios */
.magazine-author-bio .wp-block-author-profile-blocks-author-profile {
	background: #f8f9fa;
	border-left: 5px solid #3498db;
	padding: 30px;
	margin: 30px 0;
	display: flex;
	align-items: center;
	gap: 30px;
}

.magazine-author-bio .apb-author-image img {
	width: 150px;
	height: 150px;
	border-radius: 50%;
	border: 4px solid white;
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.magazine-author-bio .author-content {
	flex: 1;
}

.magazine-author-bio .apb-author-name {
	font-size: 2rem;
	font-weight: 300;
	color: #2c3e50;
	margin-bottom: 5px;
	font-family: "Georgia", serif;
}

.magazine-author-bio .apb-author-position {
	color: #3498db;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 1px;
	font-size: 0.9rem;
	margin-bottom: 15px;
}

.magazine-author-bio .apb-author-description {
	font-size: 1.1rem;
	line-height: 1.7;
	color: #34495e;
	margin-bottom: 20px;
}

.magazine-author-bio .apb-social-profiles {
	margin-top: 20px;
}

.magazine-author-bio .apb-social-profiles a {
	display: inline-block;
	width: 40px;
	height: 40px;
	background: #3498db;
	color: white;
	border-radius: 50%;
	text-align: center;
	line-height: 40px;
	margin-right: 10px;
	transition: all 0.3s ease;
}

.magazine-author-bio .apb-social-profiles a:hover {
	background: #2980b9;
	transform: translateY(-2px);
}
```

### Minimalist Author Cards

Create clean, minimalist author cards:

```css
/* Minimalist author cards */
.minimalist-cards .wp-block-author-profile-blocks-author-grid {
	background: #ffffff;
	padding: 40px 0;
}

.minimalist-cards .apb-author-grid-item {
	background: transparent;
	border: 1px solid #e1e8ed;
	border-radius: 8px;
	padding: 30px 20px;
	text-align: center;
	transition: all 0.3s ease;
}

.minimalist-cards .apb-author-grid-item:hover {
	border-color: #3498db;
	box-shadow: 0 5px 20px rgba(52, 152, 219, 0.1);
}

.minimalist-cards .apb-author-image img {
	width: 80px;
	height: 80px;
	border-radius: 50%;
	margin: 0 auto 20px;
	border: 2px solid #e1e8ed;
}

.minimalist-cards .apb-author-name {
	font-size: 1.2rem;
	font-weight: 600;
	color: #2c3e50;
	margin-bottom: 5px;
}

.minimalist-cards .apb-author-position {
	color: #7f8c8d;
	font-size: 0.9rem;
	margin-bottom: 15px;
}

.minimalist-cards .apb-author-description {
	color: #34495e;
	font-size: 0.85rem;
	line-height: 1.5;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
```

### Dark Mode Support

Add dark mode support for modern websites:

```css
/* Dark mode support */
@media (prefers-color-scheme: dark) {
	.wp-block-author-profile-blocks-author-profile,
	.wp-block-author-profile-blocks-author-grid,
	.wp-block-author-profile-blocks-author-carousel,
	.wp-block-author-profile-blocks-author-list {
		background-color: #1a1a1a;
		color: #e0e0e0;
		border-color: #333;
	}

	.apb-author-name {
		color: #ffffff;
	}

	.apb-author-position {
		color: #b0b0b0;
	}

	.apb-author-description {
		color: #d0d0d0;
	}

	.apb-social-profiles a {
		color: #e0e0e0;
		background-color: #333;
	}

	.apb-social-profiles a:hover {
		background-color: #444;
	}
}

/* Manual dark mode toggle */
.dark-mode .wp-block-author-profile-blocks-author-profile,
.dark-mode .wp-block-author-profile-blocks-author-grid,
.dark-mode .wp-block-author-profile-blocks-author-carousel,
.dark-mode .wp-block-author-profile-blocks-author-list {
	background-color: #1a1a1a;
	color: #e0e0e0;
	border-color: #333;
}

.dark-mode .apb-author-name {
	color: #ffffff;
}

.dark-mode .apb-author-position {
	color: #b0b0b0;
}

.dark-mode .apb-author-description {
	color: #d0d0d0;
}
```

### Interactive Author Cards

Add interactive elements to author cards:

```css
/* Interactive author cards */
.interactive-cards .apb-author-grid-item {
	position: relative;
	overflow: hidden;
	cursor: pointer;
}

.interactive-cards .apb-author-grid-item::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: linear-gradient(45deg, #3498db, #2980b9);
	opacity: 0;
	transition: opacity 0.3s ease;
	z-index: 1;
}

.interactive-cards .apb-author-grid-item:hover::before {
	opacity: 0.9;
}

.interactive-cards .apb-author-grid-item > * {
	position: relative;
	z-index: 2;
}

.interactive-cards .apb-author-grid-item:hover .apb-author-name,
.interactive-cards .apb-author-grid-item:hover .apb-author-position,
.interactive-cards .apb-author-grid-item:hover .apb-author-description {
	color: white;
}

.interactive-cards .author-overlay {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	opacity: 0;
	transition: opacity 0.3s ease;
	z-index: 3;
}

.interactive-cards .apb-author-grid-item:hover .author-overlay {
	opacity: 1;
}

.interactive-cards .author-overlay-content {
	text-align: center;
	color: white;
}

.interactive-cards .author-overlay-content h4 {
	margin: 0 0 10px 0;
	font-size: 1.2rem;
}

.interactive-cards .author-overlay-content .author-links {
	display: flex;
	gap: 10px;
	justify-content: center;
}

.interactive-cards .author-overlay-content .author-links a {
	display: inline-block;
	padding: 8px 16px;
	background: rgba(255, 255, 255, 0.2);
	color: white;
	text-decoration: none;
	border-radius: 20px;
	font-size: 0.9rem;
	transition: background 0.3s ease;
}

.interactive-cards .author-overlay-content .author-links a:hover {
	background: rgba(255, 255, 255, 0.3);
}
```

## Theme Integration Examples

### Twenty Twenty-Four Theme

```css
/* Author Profile Blocks integration with Twenty Twenty-Four Theme */
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid,
.wp-block-author-profile-blocks-author-carousel,
.wp-block-author-profile-blocks-author-list {
	--wp--preset--color--background: var(--wp--preset--color--base);
	--wp--preset--color--foreground: var(--wp--preset--color--contrast);

	font-family: var(--wp--preset--font-family--system);
	background-color: var(--wp--preset--color--background);
	color: var(--wp--preset--color--foreground);
}

.apb-author-name {
	font-family: var(--wp--preset--font-family--heading);
	font-weight: var(--wp--custom--typography--font-weight--bold);
}

.apb-author-description {
	font-size: var(--wp--preset--font-size--small);
}
```

### Kadence Theme

```css
/* Author Profile Blocks integration with Kadence Theme */
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid,
.wp-block-author-profile-blocks-author-carousel,
.wp-block-author-profile-blocks-author-list {
	--apb-primary: var(--global-palette1);
	--apb-secondary: var(--global-palette2);
	--apb-text: var(--global-palette4);
	--apb-background: var(--global-palette9);
	--apb-accent: var(--global-palette3);

	font-family: var(--global-body-font-family);
	background-color: var(--apb-background);
	color: var(--apb-text);
}

.apb-author-name {
	font-family: var(--global-heading-font-family);
	color: var(--apb-primary);
}

.apb-author-position {
	color: var(--apb-secondary);
}
```

## Performance Optimization

For sites with many authors or multiple blocks, consider these optimization tips:

1. **Limit Maximum Authors**: Use the "Maximum Authors" setting to limit the number of authors displayed in Grid, Carousel, and List blocks

2. **Disable Unused Elements**: Hide elements you don't need (like description or social links) to reduce DOM size

3. **Optimize Images**: Use properly sized images for authors (ideally 150×150 pixels for most displays)

4. **Lazy Loading**: The plugin automatically uses lazy loading for images, but you can further optimize with additional lazy loading plugins

5. **Caching**: Use a caching plugin to improve performance

For developers, you can also modify the internal caching behavior:

```php
// Adjust cache expiration time (default is HOUR_IN_SECONDS)
add_filter( 'author_profile_blocks_cache_expiration', function() {
    return DAY_IN_SECONDS; // Cache for one day
});

// Disable caching completely
add_filter( 'author_profile_blocks_enable_caching', '__return_false' );
```

## Accessibility Considerations

Author Profile Blocks are built with accessibility in mind, but you can further enhance accessibility:

1. **High Contrast Colors**: Ensure text colors have sufficient contrast against background colors

2. **Focus Styles**: Enhance keyboard focus styles for interactive elements:

```css
.apb-social-profiles a:focus,
.apb-author-email a:focus {
	outline: 2px solid #3498db;
	outline-offset: 2px;
}
```

3. **ARIA Attributes**: Add additional ARIA attributes for custom integrations:

```php
add_filter( 'author_profile_blocks_rendered_block', function( $content, $block, $block_name ) {
    // Add ARIA attributes to carousel navigation
    if ( $block_name === 'author-carousel' ) {
        $content = str_replace(
            '<button class="slick-prev">',
            '<button class="slick-prev" aria-label="Previous slide">',
            $content
        );
        $content = str_replace(
            '<button class="slick-next">',
            '<button class="slick-next" aria-label="Next slide">',
            $content
        );
    }
    return $content;
}, 10, 3 );
```

## Troubleshooting Customizations

If your customizations aren't working as expected, check these common issues:

1. **CSS Specificity**: Ensure your CSS selectors are specific enough to override default styles

2. **Theme Conflicts**: Some themes apply very specific styles that may conflict with your customizations

3. **Caching**: Clear your browser cache and any server-side caching after making changes

4. **Plugin Updates**: Custom CSS may need updates after plugin updates

For advanced debugging, you can enable development mode:

```php
// Enable development mode (disables caching)
add_filter( 'author_profile_blocks_development_mode', '__return_true' );
```
