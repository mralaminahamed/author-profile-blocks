---
layout: default
title: Author Grid Block
parent: Blocks
nav_order: 2
permalink: /blocks/author-grid/
---

# Author Grid Block

{: .no_toc }

The Author Grid Block allows you to display multiple authors in a responsive grid layout, perfect for team pages or contributor listings.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

![Author Grid Block]({{ site.baseurl }}/assets/images/author-grid-block.png)

## Overview

The Author Grid Block is designed to showcase multiple authors in a clean, responsive grid layout. It's perfect for:

- Team pages
- Contributor listings
- Faculty directories
- Speaker showcases
- Expert panels

## Block Settings

The Author Grid Block offers a variety of settings to customize both the content and appearance.

### Grid Settings

- **Columns**: Number of columns to display (1-4)
- **Item Spacing**: Space between grid items (0-50px)
- **Maximum Authors**: Limit the number of authors displayed (1-50)
- **Filter by Role**: Optionally filter authors by their WordPress role (All, Administrator, Editor, Author, Contributor)

### Display Settings

- **Show Author Image**: Toggle the author's profile picture
- **Show Author Position**: Toggle the author's title/position
- **Show Author Email**: Toggle the author's email address
- **Show Author Description**: Toggle the author's biographical information
- **Show Member Since Date**: Toggle the author's registration date with customizable label
- **Show Social Links**: Toggle the author's social media profiles

### Style Settings

- **Item Padding**: Space inside each author card (0-50px)
- **Enable Shadow**: Add a subtle shadow effect to each card
- **Enable Rounded Corners**: Add rounded corners to each card
- **Enable Border**: Add a border around each card
- **Border Width**: Width of the border (1-10px)
- **Background Color**: Custom color for each card
- **Border Color**: Custom color for the card border
- **Text Alignment**: Left, center, or right alignment for content

## Layout Options

The Author Grid Block offers multiple layout options to fit your design needs:

### Card Layout

The Card layout presents each author in a card format with:

- Author image at the top
- Name and position below
- Email, registration date, and description
- Social links at the bottom

Ideal for formal team pages.

![Card Layout]({{ site.baseurl }}/assets/images/author-grid-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:

- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal when displaying many authors.

![Compact Layout]({{ site.baseurl }}/assets/images/author-grid-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:

- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for highlighting featured team members.

![Centered Layout]({{ site.baseurl }}/assets/images/author-grid-centered-layout.png)

## Responsive Behavior

The Author Grid automatically adjusts based on screen size:

- **Desktop**: Displays the number of columns specified (1-4)
- **Tablet**: Automatically reduces to 2 columns
- **Mobile**: Switches to a single column for optimal mobile viewing

## Usage Examples

### Basic Team Page (3 Columns)

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "columns": 3,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "card",
  "enableShadow": true,
  "enableRounded": true
} /-->
```

### Contributor Showcase (4 Columns, Compact)

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8],
  "columns": 4,
  "showImage": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "compact"
} /-->
```

### Featured Authors (2 Columns, Centered)

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2],
  "columns": 2,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "centered",
  "enableShadow": true,
  "enableRounded": true,
  "padding": 30
} /-->
```

## Advanced Configuration Examples

### Corporate Team Grid with Custom Styling

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "columns": 3,
  "filterRole": "author",
  "maxAuthors": 6,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "card",
  "textAlign": "center",
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "#f8f9fa",
  "textColor": "#2c3e50",
  "padding": 25,
  "itemSpacing": 20,
  "enableShadow": true,
  "enableBorder": true,
  "borderColor": "#e9ecef",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 8
} /-->
```

### Minimalist Author Cards

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3, 4],
  "columns": 2,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "compact",
  "textAlign": "left",
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "transparent",
  "padding": 15,
  "itemSpacing": 30,
  "enableShadow": false,
  "enableBorder": true,
  "borderColor": "#dee2e6",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 4
} /-->
```

### Editorial Team Showcase

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3],
  "columns": 3,
  "filterRole": "editor",
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showSocial": true,
  "layout": "centered",
  "textAlign": "center",
  "backgroundColor": "#f8f9fa",
  "itemBackgroundColor": "#ffffff",
  "textColor": "#495057",
  "padding": 30,
  "itemSpacing": 25,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 12
} /-->
```

### Contributor Wall (Large Grid)

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
  "columns": 4,
  "maxAuthors": 12,
  "showImage": true,
  "showPosition": false,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": false,
  "showSocial": false,
  "layout": "compact",
  "textAlign": "center",
  "backgroundColor": "#f8f9fa",
  "itemBackgroundColor": "#ffffff",
  "padding": 20,
  "itemSpacing": 15,
  "enableShadow": false,
  "enableBorder": true,
  "borderColor": "#e9ecef",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 6
} /-->
```

### Leadership Team (2 Columns, Formal)

```
<!-- wp:author-profile-blocks/author-grid {
  "authorIds": [1, 2],
  "columns": 2,
  "filterRole": "administrator",
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "card",
  "textAlign": "left",
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "#f8f9fa",
  "textColor": "#2c3e50",
  "padding": 35,
  "itemSpacing": 40,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 8
} /-->
```

### Dynamic Author Grid with Filtering

#### Role-Based Filtering Example

```
<!-- wp:author-profile-blocks/author-grid {
  "columns": 3,
  "filterRole": "author",
  "maxAuthors": 9,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "card",
  "enableShadow": true,
  "enableRounded": true
} /-->
```

This configuration will automatically display all users with the "Author" role, limited to 9 authors maximum.

#### Editorial Staff Only

```
<!-- wp:author-profile-blocks/author-grid {
  "columns": 2,
  "filterRole": "editor",
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "centered",
  "enableShadow": true,
  "enableRounded": true
} /-->
```

### Programmatic Block Creation

#### Creating Grids with Custom Author Queries

```php
// Display authors from a specific department
$department_authors = get_users(array(
    'meta_key' => 'department',
    'meta_value' => 'engineering',
    'number' => 8
));

$author_ids = wp_list_pluck($department_authors, 'ID');

echo author_profile_blocks_render_block('author-grid', array(
    'selectedAuthors' => $author_ids,
    'columns' => 4,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => false,
    'showSocial' => true,
    'layout' => 'compact',
    'enableShadow' => true,
    'enableRounded' => true
));
```

#### Featured Authors on Homepage

```php
// Get recently active authors
$recent_authors = get_users(array(
    'number' => 6,
    'orderby' => 'post_count',
    'order' => 'DESC',
    'has_published_posts' => true
));

$author_ids = wp_list_pluck($recent_authors, 'ID');

echo author_profile_blocks_render_block('author-grid', array(
    'selectedAuthors' => $author_ids,
    'columns' => 3,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => true,
    'showSocial' => true,
    'layout' => 'card',
    'enableShadow' => true,
    'enableRounded' => true,
    'textAlign' => 'center'
));
```

### Integration with Custom Fields

#### ACF Integration Example

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('get_field')) {
        // Add department information
        $author_data['department'] = get_field('department', 'user_' . $user->ID);

        // Add custom profile image
        $custom_image = get_field('profile_image', 'user_' . $user->ID);
        if ($custom_image) {
            $author_data['image'] = $custom_image['sizes']['medium'];
        }

        // Add expertise tags
        $author_data['expertise'] = get_field('expertise', 'user_' . $user->ID);
    }

    return $author_data;
}, 10, 2);
```

#### Custom Sorting by Department

```php
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    // Sort authors by department, then by name
    usort($authors, function($a, $b) {
        $dept_a = get_user_meta($a['id'], 'department', true) ?: 'zzz';
        $dept_b = get_user_meta($b['id'], 'department', true) ?: 'zzz';

        if ($dept_a === $dept_b) {
            return strcmp($a['title'], $b['title']);
        }

        return strcmp($dept_a, $dept_b);
    });

    return $authors;
}, 10, 2);
```

### Advanced Styling Examples

#### Gradient Background Grid

```css
/* Custom CSS for gradient background */
.wp-block-author-profile-blocks-author-grid {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	padding: 40px 20px;
	border-radius: 15px;
}

.apb-author-grid-item {
	background: rgba(255, 255, 255, 0.95);
	backdrop-filter: blur(10px);
	border: none !important;
}
```

#### Hover Animation Effects

```css
/* Enhanced hover effects */
.apb-author-grid-item {
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.apb-author-grid-item:hover {
	transform: translateY(-8px);
	box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.apb-author-grid-item:hover .apb-author-image img {
	transform: scale(1.05);
}
```

#### Department-Based Color Coding

```php
add_filter('author_profile_blocks_rendered_author_grid', function($content, $block) {
    // Add department-based color coding
    $content = preg_replace_callback(
        '/data-author-id="(\d+)"/',
        function($matches) {
            $user_id = $matches[1];
            $department = get_user_meta($user_id, 'department', true);

            $color_class = '';
            switch ($department) {
                case 'engineering':
                    $color_class = 'department-engineering';
                    break;
                case 'marketing':
                    $color_class = 'department-marketing';
                    break;
                case 'sales':
                    $color_class = 'department-sales';
                    break;
            }

            return $matches[0] . ' data-department="' . esc_attr($department) . '" class="' . $color_class . '"';
        },
        $content
    );

    return $content;
}, 10, 2);
```

### Performance Optimization

#### Large Author Base Handling

```php
// For sites with many authors, implement pagination
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    $page = isset($_GET['author_page']) ? intval($_GET['author_page']) : 1;
    $per_page = 12; // Show 12 authors per page

    $offset = ($page - 1) * $per_page;
    return array_slice($authors, $offset, $per_page);
}, 10, 2);
```

#### Lazy Loading for Better Performance

```php
add_filter('author_profile_blocks_rendered_author_grid', function($content, $block) {
    // Add lazy loading class for performance
    if (isset($block['lazyLoad']) && $block['lazyLoad']) {
        $content = str_replace(
            'wp-block-author-profile-blocks-author-grid',
            'wp-block-author-profile-blocks-author-grid lazy-load',
            $content
        );
    }

    return $content;
}, 10, 2);
```

## Tips and Best Practices

- Use consistent image sizes for all authors
- For larger teams, use the Compact layout to save space
- Keep descriptions brief to maintain visual consistency
- 3 columns works well for most websites
- 2 columns is ideal for featured authors with longer descriptions
- 4 columns works well for larger screens when showing minimal information

## Author Selection

### Using the Author Picker

The Author Grid Block includes an intuitive author picker that allows you to:

1. Search for specific authors by name
2. Filter authors by role
3. Select multiple authors
4. Reorder selected authors by dragging
5. Remove authors from the selection

![Author Picker]({{ site.baseurl }}/assets/images/author-picker.png)

### Setting a Maximum Display Limit

You can set a maximum number of authors to display using the "Maximum Authors" setting. This is useful when:

- You want to show only the most recent team members
- You need to limit the number of authors for performance reasons
- You're creating a "Featured Team Members" section

If the Maximum Authors setting is less than the number of selected authors, only the first X authors will be displayed.

## Related Blocks

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) - For displaying a single author in detail
- [Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %}) - For showcasing authors in an interactive carousel
- [Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %}) - For displaying authors in a list format
