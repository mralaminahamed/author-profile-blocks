---
layout: default
title: Author Profile Block
parent: Blocks
nav_order: 1
permalink: /blocks/author-profile/
---

# Author Profile Block

{: .no_toc }

The Author Profile Block allows you to showcase detailed information about a single author.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

![Author Profile Block]({{ site.baseurl }}/assets/images/author-profile-block.png)

## Overview

The Author Profile Block is designed to display comprehensive information about a single author. It's perfect for:

- Author bio pages
- Team member profiles
- About pages
- Sidebar author information
- Post signature blocks

## Block Settings

The Author Profile Block offers a variety of settings to customize both the content and appearance.

### Content Settings

- **Author Selection**: Choose any user from your WordPress site
- **Show Image**: Toggle the author's profile picture
- **Show Position**: Toggle the author's title/position
- **Show Email**: Toggle the author's email address
- **Show Description**: Toggle the author's biographical information
- **Show Member Since Date**: Toggle the author's registration date with customizable label
- **Show Social Links**: Toggle the author's social media profiles
- **Show More Section**: Toggle additional custom content
- **More Content**: Add custom content about the author (when "Show More Section" is enabled)

### Style Settings

- **Text Alignment**: Left, center, or right alignment
- **Background Color**: Custom color for the block
- **Padding**: Space around the content (0-50px)
- **Shadow**: Add subtle shadow effect
- **Border**: Add customizable border
- **Border Color**: Color for the border
- **Border Width**: Width of the border (1-10px)
- **Rounded Corners**: Add rounded corners

## Layout Options

The Author Profile Block offers multiple layout options to fit your design needs:

### Card Layout

The Card layout presents the author information in a card format with:

- Author image at the top
- Name and position below
- Email, registration date, and description
- Social links at the bottom

Ideal for formal presentation of authors.

![Card Layout]({{ site.baseurl }}/assets/images/author-profile-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:

- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal for sidebar widgets or when space is limited.

![Compact Layout]({{ site.baseurl }}/assets/images/author-profile-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:

- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for author spotlights or featured authors.

![Centered Layout]({{ site.baseurl }}/assets/images/author-profile-centered-layout.png)

## Usage Examples

### Author Bio

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "textAlign": "left",
  "layout": "card"
} /-->
```

### Author Spotlight

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "textAlign": "center",
  "layout": "centered",
  "enableShadow": true,
  "enableRounded": true
} /-->
```

### Compact Sidebar

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "compact"
} /-->
```

### Advanced Configuration Examples

#### Complete Author Profile with Custom Styling

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 2,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showSocial": true,
  "showMoreSection": true,
  "moreContent": "<p>This author specializes in WordPress development and has been contributing to the community for over 5 years. They have extensive experience with Gutenberg blocks, theme development, and plugin architecture.</p><p><strong>Specialties:</strong> PHP, JavaScript, React, WordPress Core</p>",
  "layout": "card",
  "textAlign": "left",
  "backgroundColor": "#f8f9fa",
  "textColor": "#2c3e50",
  "padding": 30,
  "enableShadow": true,
  "enableBorder": true,
  "borderColor": "#e9ecef",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 8
} /-->
```

#### Minimal Author Card

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 3,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "compact",
  "textAlign": "center",
  "backgroundColor": "#ffffff",
  "padding": 20,
  "enableShadow": false,
  "enableBorder": true,
  "borderColor": "#dee2e6",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 4
} /-->
```

#### Featured Author with Rich Content

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showSocial": true,
  "showMoreSection": true,
  "moreContent": "<h4>Recent Achievements</h4><ul><li>Published 50+ articles on WordPress development</li><li>Spoke at 10+ WordPress conferences</li><li>Contributed to WordPress core</li><li>Mentored 20+ developers</li></ul><h4>Connect</h4><p>Available for consulting, speaking engagements, and WordPress development projects.</p>",
  "layout": "centered",
  "textAlign": "center",
  "backgroundColor": "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
  "textColor": "#ffffff",
  "padding": 40,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 12
} /-->
```

#### Professional Bio Layout

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 4,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "showMoreSection": true,
  "moreContent": "<div class=\"professional-bio\"><h4>Professional Background</h4><p>With over 8 years of experience in web development, I specialize in creating high-performance WordPress solutions for enterprise clients.</p><h4>Expertise</h4><ul><li>Custom WordPress Plugin Development</li><li>E-commerce Solutions</li><li>Performance Optimization</li><li>Security Hardening</li></ul></div>",
  "layout": "card",
  "textAlign": "left",
  "backgroundColor": "#2c3e50",
  "textColor": "#ecf0f1",
  "padding": 35,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 6
} /-->
```

### Dynamic Author Selection

#### Using Block Context for Dynamic Authors

```php
// In your theme's functions.php or a custom plugin

// Add support for author context
add_filter('author_profile_blocks_block_context', function($context, $block) {
    // Get author from URL parameter
    if (isset($_GET['author_id'])) {
        $context['authorId'] = intval($_GET['author_id']);
    }

    // Get author from current post
    if (is_single() && empty($context['authorId'])) {
        $context['authorId'] = get_the_author_meta('ID');
    }

    return $context;
}, 10, 2);

// Create a dynamic author profile shortcode
function dynamic_author_profile_shortcode($atts) {
    $atts = shortcode_atts(array(
        'author_id' => get_the_author_meta('ID'),
        'layout' => 'card',
        'show_social' => 'true',
        'show_description' => 'true'
    ), $atts);

    return author_profile_blocks_render_block('author-profile', array(
        'authorId' => intval($atts['author_id']),
        'showImage' => true,
        'showPosition' => true,
        'showEmail' => true,
        'showDescription' => $atts['show_description'] === 'true',
        'showRegisteredDate' => false,
        'showSocial' => $atts['show_social'] === 'true',
        'layout' => $atts['layout'],
        'textAlign' => 'left'
    ));
}
add_shortcode('dynamic_author_profile', 'dynamic_author_profile_shortcode');
```

#### Author Profile in Page Templates

```php
// In your page template
get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();

        // Get the featured author for this page
        $featured_author_id = get_post_meta(get_the_ID(), 'featured_author_id', true);

        if ($featured_author_id) {
            echo author_profile_blocks_render_block('author-profile', array(
                'authorId' => $featured_author_id,
                'showImage' => true,
                'showPosition' => true,
                'showDescription' => true,
                'showSocial' => true,
                'layout' => 'centered',
                'textAlign' => 'center',
                'enableShadow' => true,
                'enableRounded' => true
            ));
        }

        the_content();
    }
}

get_footer();
```

### Integration with Custom Fields

#### Using ACF for Enhanced Author Data

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('get_field')) {
        // Add ACF fields to author data
        $author_data['company'] = get_field('company', 'user_' . $user->ID);
        $author_data['certifications'] = get_field('certifications', 'user_' . $user->ID);
        $author_data['years_experience'] = get_field('years_experience', 'user_' . $user->ID);

        // Add custom image if available
        $custom_image = get_field('profile_image', 'user_' . $user->ID);
        if ($custom_image) {
            $author_data['image'] = $custom_image['sizes']['medium'];
        }
    }

    return $author_data;
}, 10, 2);
```

#### Custom Author Profile Template

```php
add_filter('author_profile_blocks_rendered_author_profile', function($content, $block) {
    // Create a custom template for specific authors
    $author_id = $block['authorId'];
    $author = get_userdata($author_id);

    if ($author && in_array('premium_author', $author->roles)) {
        // Custom premium author template
        ob_start();
        ?>
        <div class="premium-author-profile">
            <div class="author-badge">Premium Author</div>
            <div class="author-content">
                <?php echo $content; ?>
            </div>
            <div class="author-stats">
                <span>Articles: <?php echo count_user_posts($author_id); ?></span>
                <span>Since: <?php echo date('M Y', strtotime($author->user_registered)); ?></span>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    return $content;
}, 10, 2);
```

## Tips and Best Practices

- Use high-quality profile pictures for better visual appeal
- Keep author descriptions concise for better readability
- For consistent styling, match the block's background color with your theme
- Use the same layout for all authors on the same page for visual consistency
- Add the "More Content" section for additional information like recent posts or achievements

## Using the More Content Section

The More Content section provides a space for adding additional information about the author that might not fit in the standard description field. This can include:

1. Recent posts by the author
2. Awards and achievements
3. Current projects
4. Contact information
5. Availability for speaking engagements
6. Office hours or scheduling information

This section supports rich text formatting including bold, italic, links, and lists.

## Related Blocks

- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) - For displaying multiple authors in a grid
- [Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %}) - For showcasing multiple authors in a carousel
- [Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %}) - For displaying multiple authors in a list format
