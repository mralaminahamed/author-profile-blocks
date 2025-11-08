---
layout: default
title: Author List Block
parent: Blocks
nav_order: 4
permalink: /blocks/author-list/
---

# Author List Block

{: .no_toc }

The Author List Block allows you to display multiple authors in a customizable list format, perfect for team directories, contributor listings, or any situation where a vertical arrangement is preferred.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

![Author List Block]({{ site.baseurl }}/assets/images/author-list-block.png)

## Overview

The Author List Block is designed to showcase multiple authors in a clean, organized list format. It's perfect for:

- Team directories
- Contributor pages
- Faculty listings
- Staff pages
- Event speaker rosters

## Block Settings

The Author List Block offers a variety of settings to customize both the content and appearance.

### Author Selection

- **Author Selection**: Choose specific authors to include in your list
- **Filter by Role**: Optionally filter authors by their WordPress role (All, Administrator, Editor, Author, Contributor)
- **Maximum Authors**: Limit the number of authors displayed (0 = show all selected)

### List Settings

- **Display Style**:
    - **Compact**: Space-efficient layout showing essential information
    - **Detailed**: Expanded layout with more author details
    - **Minimal**: Clean, streamlined layout with only the most essential information
- **List Style**:
    - **Unordered List**: Standard bulleted list format
    - **Ordered List**: Numbered list format
- **Show Dividers**: Add separators between list items
- **Divider Color**: Customize the color of dividers
- **Rounded Corners**: Add rounded corners to list items
- **Hover Effect**: Add interactive hover animation
- **Space Between Items**: Adjust vertical spacing between authors (0-50px)
- **Block Padding**: Adjust padding around the entire block (0-50px)
- **Item Padding**: Adjust padding within each list item (0-50px)

### Colors

- **Block Background**: Set a background color for the entire list block
- **Item Background**: Set a background color for individual list items

### Display Elements

- **Show Author Image**: Toggle display of author profile pictures
- **Show Position/Role**: Toggle display of author title/position
- **Show Email**: Toggle display of author email address
- **Show Description**: Toggle display of author biographical information
- **Show Social Links**: Toggle display of author social media profiles

## Display Styles

The Author List block offers three distinct display styles to suit different needs:

### Compact Style

The Compact style presents each author in a streamlined format:

- Author image on the left
- Name and position in the middle
- Social links on the right (optional)

This style is ideal for creating concise lists with many authors, minimizing vertical space while providing essential information.

![Compact Style]({{ site.baseurl }}/assets/images/author-list-compact-style.png)

### Detailed Style

The Detailed style provides a more comprehensive presentation:

- Author image on the left
- Name, position, email, and description to the right
- Social links at the bottom

This style is perfect for more in-depth team directories where you want to showcase more information about each author.

![Detailed Style]({{ site.baseurl }}/assets/images/author-list-detailed-style.png)

### Minimal Style

The Minimal style provides the cleanest, most streamlined presentation:

- Author image on the left
- Name and position only (no email, description, or social links)

This style is perfect for situations where you want to showcase team members with minimal information, creating a clean and professional appearance.

![Minimal Style]({{ site.baseurl }}/assets/images/author-list-minimal-style.png)

## List Style Options

Choose between two list formats:

- **Unordered List**: Traditional bulleted list format (•)
- **Ordered List**: Sequential numbered list format (1, 2, 3...)

## Responsive Behavior

The Author List block automatically adapts to different screen sizes:

- **Desktop**: Displays both compact and detailed layouts as configured
- **Mobile**:
    - Detailed layout switches to a stacked format with the image centered
    - Compact layout maintains its horizontal arrangement with adjusted spacing

## Usage Examples

### Team Directory (Detailed Style)

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "detailed",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 20,
  "itemPadding": 15
} /-->
```

### Clean Team List (Minimal Style)

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->
```

### Contributors List (Compact Style)

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8],
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 10
} /-->
```

### Numbered Author List

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3],
  "displayStyle": "detailed",
  "listStyle": "ol",
  "enableDividers": false,
  "enableRounded": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemBackgroundColor": "#f7f7f7",
  "itemSpacing": 15,
  "itemPadding": 20
} /-->
```

## Advanced Configuration Examples

### Corporate Directory with Alternating Colors

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": false,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showSocial": true,
  "itemSpacing": 5,
  "itemPadding": 20,
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "#f8f9fa"
} /-->
```

_Add this CSS for alternating row colors:_

```css
.apb-author-list-item:nth-child(odd) {
	background-color: #f8f9fa !important;
}

.apb-author-list-item:nth-child(even) {
	background-color: #ffffff !important;
}
```

### Executive Leadership List

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3],
  "filterRole": "administrator",
  "displayStyle": "detailed",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showDescription": true,
  "showSocial": true,
  "itemSpacing": 25,
  "itemPadding": 30,
  "backgroundColor": "#2c3e50",
  "itemBackgroundColor": "#34495e",
  "textColor": "#ecf0f1"
} /-->
```

### Department-Based Author List

```
<!-- wp:author-profile-blocks/author-list {
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showSocial": true,
  "itemSpacing": 15,
  "itemPadding": 20,
  "backgroundColor": "#f8f9fa"
} /-->
```

_This configuration will display all authors, which can then be filtered by department using custom code._

### Minimal Contact Directory

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": false,
  "enableHoverEffect": false,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showSocial": false,
  "itemSpacing": 8,
  "itemPadding": 12,
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "transparent"
} /-->
```

### Interactive Staff Directory

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": true,
  "showSocial": true,
  "itemSpacing": 12,
  "itemPadding": 18,
  "backgroundColor": "#ffffff",
  "itemBackgroundColor": "#f8f9fa"
} /-->
```

### Programmatic List Creation

#### Department-Filtered List

```php
// Display authors from a specific department
$engineering_authors = get_users(array(
    'meta_key' => 'department',
    'meta_value' => 'engineering',
    'number' => 10
));

$author_ids = wp_list_pluck($engineering_authors, 'ID');

echo author_profile_blocks_render_block('author-list', array(
    'selectedAuthors' => $author_ids,
    'displayStyle' => 'compact',
    'listStyle' => 'ul',
    'enableDividers' => true,
    'enableHoverEffect' => true,
    'showImage' => true,
    'showPosition' => true,
    'showEmail' => true,
    'showSocial' => true,
    'itemSpacing' => 15,
    'itemPadding' => 20
));
```

#### Recent Contributors List

```php
// Get authors with recent posts
$recent_authors = get_users(array(
    'number' => 8,
    'orderby' => 'post_count',
    'order' => 'DESC',
    'has_published_posts' => true
));

$author_ids = wp_list_pluck($recent_authors, 'ID');

echo author_profile_blocks_render_block('author-list', array(
    'selectedAuthors' => $author_ids,
    'displayStyle' => 'detailed',
    'listStyle' => 'ol',
    'enableDividers' => true,
    'enableRounded' => true,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => true,
    'showSocial' => true,
    'itemSpacing' => 20,
    'itemPadding' => 25
));
```

### Custom Styling Examples

#### Card-Based List Design

```css
/* Transform list into card layout */
.apb-author-list.card-style .apb-author-list-item {
	display: inline-block;
	width: calc(50% - 10px);
	margin: 0 5px 20px;
	vertical-align: top;
	background: white;
	border: 1px solid #e9ecef;
	border-radius: 8px;
	padding: 20px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	transition: transform 0.2s ease;
}

.apb-author-list.card-style .apb-author-list-item:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
	.apb-author-list.card-style .apb-author-list-item {
		width: 100%;
		margin: 0 0 20px;
	}
}
```

#### Magazine-Style Author List

```css
/* Magazine-style layout */
.magazine-author-list .apb-author-list-item {
	border-left: 4px solid #3498db;
	padding-left: 20px;
	margin-bottom: 30px;
	background: #f8f9fa;
	padding: 25px 20px;
	border-radius: 0 8px 8px 0;
}

.magazine-author-list .apb-author-image {
	float: right;
	margin-left: 20px;
	margin-bottom: 10px;
}

.magazine-author-list .apb-author-name {
	font-size: 1.4rem;
	font-weight: 600;
	color: #2c3e50;
	margin-bottom: 5px;
}

.magazine-author-list .apb-author-position {
	color: #3498db;
	font-weight: 500;
	margin-bottom: 10px;
}
```

### Integration Examples

#### ACF Department Integration

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('get_field')) {
        // Add department information
        $author_data['department'] = get_field('department', 'user_' . $user->ID);

        // Add office location
        $author_data['office'] = get_field('office_location', 'user_' . $user->ID);

        // Add extension number
        $author_data['extension'] = get_field('phone_extension', 'user_' . $user->ID);
    }

    return $author_data;
}, 10, 2);

// Sort by department
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    usort($authors, function($a, $b) {
        $dept_a = get_user_meta($a['id'], 'department', true) ?: 'zzz';
        $dept_b = get_user_meta($b['id'], 'department', true) ?: 'zzz';
        return strcmp($dept_a, $dept_b);
    });

    return $authors;
}, 10, 2);
```

#### BuddyPress Integration

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('bp_get_profile_field_data')) {
        // Add BuddyPress profile fields
        $author_data['department'] = bp_get_profile_field_data(array(
            'field' => 'Department',
            'user_id' => $user->ID
        ));

        $author_data['office_hours'] = bp_get_profile_field_data(array(
            'field' => 'Office Hours',
            'user_id' => $user->ID
        ));

        // Add BuddyPress avatar
        if (function_exists('bp_core_fetch_avatar')) {
            $author_data['image'] = bp_core_fetch_avatar(array(
                'item_id' => $user->ID,
                'type' => 'full',
                'html' => false
            ));
        }
    }

    return $author_data;
}, 10, 2);
```

### Advanced Features

#### Alphabetical Sorting with Headers

```php
add_filter('author_profile_blocks_rendered_author_list', function($content, $block) {
    if (isset($block['groupByLetter']) && $block['groupByLetter']) {
        // Group authors by first letter of name
        $grouped_authors = array();

        // Extract and group list items
        preg_match_all('/<li[^>]*data-author-id="(\d+)"[^>]*>(.*?)<\/li>/s', $content, $matches);

        foreach ($matches[1] as $index => $author_id) {
            $user = get_userdata($author_id);
            $first_letter = strtoupper(substr($user->display_name, 0, 1));
            $grouped_authors[$first_letter][] = $matches[0][$index];
        }

        ksort($grouped_authors);

        // Rebuild content with letter headers
        $new_content = '';
        foreach ($grouped_authors as $letter => $authors) {
            $new_content .= "<h3 class='author-group-header'>{$letter}</h3><ul class='author-sublist'>";
            $new_content .= implode('', $authors);
            $new_content .= '</ul>';
        }

        // Replace the original list
        $content = preg_replace('/<ul[^>]*>.*<\/ul>/s', $new_content, $content);
    }

    return $content;
}, 10, 2);
```

#### Search and Filter Functionality

```javascript
// Add search functionality to author lists
jQuery(document).ready(function ($) {
	$(".author-list-search").each(function () {
		const $search = $(this);
		const $list = $search.next(
			".wp-block-author-profile-blocks-author-list",
		);
		const $items = $list.find(".apb-author-list-item");

		$search.on("input", function () {
			const query = $(this).val().toLowerCase();

			$items.each(function () {
				const $item = $(this);
				const text = $item.text().toLowerCase();

				if (text.includes(query)) {
					$item.show();
				} else {
					$item.hide();
				}
			});
		});
	});
});
```

#### Export Functionality

```php
// Add export button to author lists
add_filter('author_profile_blocks_rendered_author_list', function($content, $block) {
    if (isset($block['enableExport']) && $block['enableExport']) {
        $export_button = '<div class="author-list-export">
            <button class="export-btn" data-format="csv">Export as CSV</button>
            <button class="export-btn" data-format="json">Export as JSON</button>
        </div>';

        $content = $export_button . $content;
    }

    return $content;
}, 10, 2);
```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "detailed",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 20,
  "itemPadding": 15
} /-->

```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

### Contributors List (Compact Style)

```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8],
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 10
} /-->

```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

### Numbered Author List

```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3],
  "displayStyle": "detailed",
  "listStyle": "ol",
  "enableDividers": false,
  "enableRounded": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemBackgroundColor": "#f7f7f7",
  "itemSpacing": 15,
  "itemPadding": 20
} /-->

```

### Clean Team List (Minimal Style)

```

<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "minimal",
  "listStyle": "ul",
  "enableDividers": false,
  "enableRounded": true,
  "showImage": true,
  "showPosition": true,
  "itemSpacing": 15,
  "itemPadding": 10
} /-->

```

## Tips and Best Practices

- Use the minimal style for clean, professional listings with essential information only
- Use consistent image sizes for all authors to maintain visual harmony
- For long lists, the compact style works best
- Enable dividers for improved readability, especially with detailed items
- Consider using the numbered list style for "top contributors" or ranking presentations
- Keep descriptions brief when using the detailed style to maintain consistent item heights
- Use hover effects to add interactivity for better user engagement
- For alternating background colors, add a custom CSS class and use `:nth-child(odd/even)` selectors

## Hover Effects

When enabled, the hover effect adds subtle interaction feedback when users hover over list items:

1. A gentle background color transition
2. A slight shadow effect
3. (Optional) Scale transformation for a subtle zoom effect

This adds an element of interactivity to your author listings and helps users identify which item they're currently focusing on.

## Converting to Other Blocks

The Author List Block can be easily converted to other Author Profile Blocks types using the toolbar options:

1. Select the Author List Block in the editor
2. Click the "Convert to Grid" button in the toolbar

This will create a new Author Grid Block with the same authors and settings, allowing you to experiment with different presentation formats.

## Related Blocks

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) - For displaying a single author in detail
- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) - For displaying authors in a responsive grid layout
- [Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %}) - For showcasing authors in an interactive carousel
```
