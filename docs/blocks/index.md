---
layout: default
title: Blocks
nav_order: 3
has_children: true
permalink: /blocks/
---

# Author Profile Blocks

{: .no_toc }

Complete documentation for all Author Profile Blocks - powerful Gutenberg blocks for showcasing author information in beautiful, customizable layouts.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Block Overview

The Author Profile Blocks plugin provides four specialized Gutenberg blocks for displaying author information in various formats. Each block is designed for specific use cases while sharing common features for consistency.

### Quick Block Comparison

| Block               | Authors  | Layout           | Best For                | Key Features                  |
| ------------------- | -------- | ---------------- | ----------------------- | ----------------------------- |
| **Author Profile**  | 1        | Single card      | Bio pages, about pages  | Detailed info, custom content |
| **Author Grid**     | Multiple | Responsive grid  | Team pages, directories | Visual gallery, filtering     |
| **Author Carousel** | Multiple | Sliding carousel | Home pages, showcases   | Interactive, autoplay         |
| **Author List**     | Multiple | Vertical list    | Directories, rosters    | Compact, numbered options     |

## Available Blocks

### 👤 Author Profile Block

{: .d-inline-block }

[View Documentation]({{ site.baseurl }}{% link blocks/author-profile.md %}){: .btn .btn-primary .fs-4 }

Display a single author with comprehensive information and customizable layouts. Perfect for author bio pages, team member profiles, and detailed author presentations.

**Key Features:**

- Single author focus with rich detail
- Multiple layout options (Card, Compact, Centered)
- Custom "More Content" section
- Social media integration
- Advanced styling controls

---

### 📱 Author Grid Block

{: .d-inline-block }

[View Documentation]({{ site.baseurl }}{% link blocks/author-grid.md %}){: .btn .btn-primary .fs-4 }

Present multiple authors in a responsive grid layout with filtering and sorting options. Ideal for team pages, contributor listings, and visual author galleries.

**Key Features:**

- Responsive grid (1-4 columns)
- Role-based filtering
- Author selection and reordering
- Consistent card layouts
- Mobile-optimized display

---

### 🎠 Author Carousel Block

{: .d-inline-block }

[View Documentation]({{ site.baseurl }}{% link blocks/author-carousel.md %}){: .btn .btn-primary .fs-4 }

Showcase multiple authors in an interactive sliding carousel with autoplay and navigation controls. Great for featured team members and engaging author presentations.

**Key Features:**

- Smooth sliding animations
- Autoplay with customizable timing
- Navigation dots and arrows
- Touch/swipe support
- Responsive slide counts

---

### 📋 Author List Block

{: .d-inline-block }

[View Documentation]({{ site.baseurl }}{% link blocks/author-list.md %}){: .btn .btn-primary .fs-4 }

Display multiple authors in a customizable vertical list format with styling options. Perfect for team directories, contributor rosters, and organized author listings.

**Key Features:**

- Multiple display styles (Compact, Detailed, Minimal)
- Ordered/unordered list formats
- Dividers and hover effects
- Flexible spacing controls
- Clean, professional appearance

## Common Features Across All Blocks

### 📋 Content Display Options

All blocks include comprehensive content controls:

- **Author Image** - Profile pictures from Gravatar or custom uploads
- **Author Name** - Display name with customizable formatting
- **Position/Title** - Job titles, roles, or designations
- **Email Address** - Contact information with privacy controls
- **Biographical Description** - Rich text author bios
- **Member Since Date** - Registration date with custom labels
- **Social Media Links** - Facebook, Twitter, LinkedIn, Instagram, and custom URLs

### 🎨 Styling & Layout Options

Consistent styling controls across all blocks:

#### Layout Variations

- **Card Layout** - Traditional card-based presentation
- **Compact Layout** - Space-efficient horizontal layouts
- **Centered Layout** - Formal, centered presentations (Profile, Grid, Carousel)
- **Detailed Layout** - Expanded information display (List block)

#### Visual Customization

- **Background Colors** - Custom colors for blocks and individual items
- **Text Alignment** - Left, center, or right alignment options
- **Padding Controls** - Adjustable spacing inside and around elements
- **Shadow Effects** - Subtle depth and dimension
- **Border Styling** - Customizable borders with width and color
- **Rounded Corners** - Modern corner radius controls
- **Hover Animations** - Interactive effects for engagement

### ⚙️ Advanced Configuration

#### Author Selection & Filtering

- **Manual Selection** - Choose specific authors from your WordPress users
- **Role-Based Filtering** - Filter by user roles (Administrator, Editor, Author, etc.)
- **Maximum Limits** - Control the number of displayed authors
- **Dynamic Ordering** - Sort by name, registration date, or custom criteria

#### Responsive Behavior

- **Mobile Optimization** - Automatic adjustments for smaller screens
- **Tablet Support** - Intermediate breakpoint handling
- **Desktop Flexibility** - Full-width layouts and multi-column options

## Block Selection Guide

### When to Use Each Block

#### Use Author Profile Block When:

- You want to highlight a single author in detail
- Creating individual bio pages or about pages
- Need custom content sections beyond standard fields
- Want a formal, centered presentation
- Space allows for comprehensive author information

#### Use Author Grid Block When:

- Displaying multiple team members or contributors
- Want a visual gallery or portfolio-style layout
- Need filtering by user roles
- Space allows for card-based presentations
- Responsive design is important

#### Use Author Carousel Block When:

- Creating engaging homepage showcases
- Want to feature authors dynamically
- Space is limited but want multiple authors visible
- Adding interactive elements to draw attention
- Implementing autoplay for passive browsing

#### Use Author List Block When:

- Creating directories or rosters
- Need a compact, vertical layout
- Want numbered or bulleted list formats
- Space requires minimal horizontal footprint
- Professional, document-style presentation

## Getting Started with Blocks

### 1. Installation & Setup

Ensure the plugin is activated and you've set up author profiles in WordPress user management.

### 2. Adding Blocks to Content

1. Open any post, page, or custom post type in the block editor
2. Click the **+** button to add a new block
3. Search for "author" or browse the Author Profile Blocks section
4. Select your desired block type

### 3. Basic Configuration

1. **Select Authors** - Choose which authors to display
2. **Configure Display** - Enable/disable content elements
3. **Choose Layout** - Select appropriate layout style
4. **Apply Styling** - Customize colors, spacing, and effects

### 4. Advanced Customization

- Use block settings for quick customization
- Apply custom CSS for advanced styling
- Utilize developer hooks for programmatic control
- Integrate with themes and other plugins

## Performance Considerations

### Optimization Tips

- **Limit Author Count** - Display only necessary authors per block
- **Optimize Images** - Use appropriate image sizes and formats
- **Enable Caching** - Leverage WordPress object caching
- **Lazy Loading** - Images load as needed for better performance
- **Minified Assets** - Production builds include optimized code

### Best Practices

- Use consistent image sizes across blocks
- Keep descriptions concise for better loading
- Enable caching for improved performance
- Test blocks across different devices and themes
- Monitor page load times and optimize accordingly

## Developer Resources

### 📚 Technical Documentation

- **[Developer API Reference]({{ site.baseurl }}{% link developer-api.md %})** - Complete API documentation with hooks, filters, and examples
- **[Plugin Architecture]({{ site.baseurl }}{% link plugin-architecture.md %})** - Detailed overview of the plugin structure and design patterns
- **[Advanced Examples]({{ site.baseurl }}{% link advanced-examples.md %})** - Integration examples and customizations

### 🛠️ Development Tools

- **REST API Endpoints** - Programmatic access to author data
- **PHP Template Functions** - Direct rendering functions
- **JavaScript Components** - Reusable React components
- **Filter & Action Hooks** - Extensive customization points

### 🔧 Customization Examples

- **Theme Integration** - Matching your site's design
- **Custom Fields** - Extending author data with ACF or custom meta
- **Dynamic Content** - Context-aware author selection
- **Performance Tuning** - Advanced caching and optimization

## Support & Community

### Getting Help

- **[Troubleshooting Guide]({{ site.baseurl }}{% link troubleshooting.md %})** - Solutions for common issues
- **[FAQ]({{ site.baseurl }}{% link faq.md %})** - Frequently asked questions
- **[GitHub Issues](https://github.com/mralaminahamed/author-profile-blocks/issues)** - Bug reports and feature requests
- **[WordPress Support](https://wordpress.org/support/plugin/author-profile-blocks/)** - Community support forum

### Contributing

- **[Contributing Guide]({{ site.baseurl }}{% link contributing.md %})** - Development guidelines and workflow
- **[Code Standards]({{ site.baseurl }}{% link contributing.md %}#coding-standards)** - PHP, JavaScript, and CSS guidelines
- **[Testing]({{ site.baseurl }}{% link contributing.md %}#testing)** - Unit and integration testing

---

Ready to get started? Choose a block above to view detailed documentation, or check out the [Getting Started guide]({{ site.baseurl }}{% link getting-started.md %}) for installation and setup instructions.
