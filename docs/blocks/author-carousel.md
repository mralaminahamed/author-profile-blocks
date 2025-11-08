---
layout: default
title: Author Carousel Block
parent: Blocks
nav_order: 3
permalink: /blocks/author-carousel/
---

# Author Carousel Block

{: .no_toc }

The Author Carousel Block allows you to showcase multiple authors in an interactive, sliding carousel format.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

![Author Carousel Block]({{ site.baseurl }}/assets/images/author-carousel-block.png)

## Overview

The Author Carousel Block is designed to showcase multiple authors in an interactive, sliding carousel. It's perfect for:

- Featured team members on home pages
- Highlighting contributors in a space-efficient way
- Showcasing speakers or panelists
- Creating an engaging team presentation
- Sidebar widgets for author spotlights

## Block Settings

The Author Carousel Block offers a variety of settings to customize both the content and appearance.

### Carousel Settings

- **Slides to Show**: Number of slides visible at once (1-5)
- **Slide Spacing**: Space between slides (0-50px)
- **Autoplay**: Toggle automatic sliding
- **Autoplay Speed**: Time between slides in milliseconds (1000-10000ms)
- **Show Dots**: Toggle navigation dots
- **Show Arrows**: Toggle navigation arrows
- **Infinite Loop**: Toggle continuous looping
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

- **Item Padding**: Space inside each author slide (0-50px)
- **Enable Shadow**: Add a subtle shadow effect to each slide
- **Enable Rounded Corners**: Add rounded corners to each slide
- **Enable Border**: Add a border around each slide
- **Border Width**: Width of the border (1-10px)
- **Background Color**: Custom color for each slide
- **Border Color**: Custom color for the slide border
- **Text Alignment**: Left, center, or right alignment for content

## Layout Options

Each author in the carousel can be displayed in one of three layouts:

### Card Layout

The Card layout presents each author in a card format with:

- Author image at the top
- Name and position below
- Email, registration date, and description
- Social links at the bottom

Ideal for formal presentation.

![Card Layout]({{ site.baseurl }}/assets/images/author-carousel-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:

- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal when space is limited.

![Compact Layout]({{ site.baseurl }}/assets/images/author-carousel-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:

- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for highlighting featured authors.

![Centered Layout]({{ site.baseurl }}/assets/images/author-carousel-centered-layout.png)

## Responsive Behavior

The Author Carousel automatically adjusts based on screen size:

- **Desktop**: Displays the number of slides specified (1-5)
- **Tablet**: Automatically reduces to 2 slides
- **Mobile**: Switches to a single slide for optimal mobile viewing

## Usage Examples

### Team Showcase (3 Slides)

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "slidesToShow": 3,
  "autoplay": true,
  "showDots": true,
  "showArrows": true,
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

### Featured Contributors (1 Slide, Autoplay)

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4],
  "slidesToShow": 1,
  "autoplay": true,
  "autoplaySpeed": 5000,
  "showDots": true,
  "showArrows": false,
  "showImage": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "centered",
  "enableShadow": true
} /-->
```

### Compact Team Display (2 Slides)

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4],
  "slidesToShow": 2,
  "autoplay": false,
  "showDots": true,
  "showArrows": true,
  "showImage": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "compact"
} /-->
```

## Advanced Configuration Examples

### Homepage Hero Carousel

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4, 5],
  "slidesToShow": 1,
  "autoplay": true,
  "autoplaySpeed": 6000,
  "showDots": true,
  "showArrows": true,
  "infiniteLoop": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "centered",
  "textAlign": "center",
  "backgroundColor": "#f8f9fa",
  "itemBackgroundColor": "#ffffff",
  "textColor": "#2c3e50",
  "padding": 40,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 12
} /-->
```

### Editorial Team Spotlight

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3],
  "filterRole": "editor",
  "slidesToShow": 2,
  "autoplay": false,
  "showDots": true,
  "showArrows": true,
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
  "padding": 30,
  "enableShadow": true,
  "enableBorder": true,
  "borderColor": "#e9ecef",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 8
} /-->
```

### Minimalist Author Showcase

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4, 5, 6],
  "slidesToShow": 3,
  "autoplay": true,
  "autoplaySpeed": 4000,
  "showDots": false,
  "showArrows": false,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "compact",
  "textAlign": "center",
  "backgroundColor": "transparent",
  "itemBackgroundColor": "transparent",
  "padding": 20,
  "enableShadow": false,
  "enableBorder": false,
  "enableRounded": false
} /-->
```

### Conference Speakers Carousel

```
<!-- wp:author-profile-blocks/author-carousel {
  "authorIds": [1, 2, 3, 4],
  "slidesToShow": 1,
  "autoplay": false,
  "showDots": true,
  "showArrows": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "centered",
  "textAlign": "center",
  "backgroundColor": "#2c3e50",
  "itemBackgroundColor": "#34495e",
  "textColor": "#ecf0f1",
  "padding": 50,
  "enableShadow": true,
  "enableBorder": false,
  "enableRounded": true,
  "roundedSize": 15
} /-->
```

### Department Showcase with Filtering

```
<!-- wp:author-profile-blocks/author-carousel {
  "slidesToShow": 2,
  "autoplay": true,
  "autoplaySpeed": 7000,
  "showDots": true,
  "showArrows": true,
  "showImage": true,
  "showPosition": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": false,
  "showSocial": true,
  "layout": "card",
  "textAlign": "left",
  "backgroundColor": "#f8f9fa",
  "itemBackgroundColor": "#ffffff",
  "padding": 25,
  "enableShadow": true,
  "enableBorder": true,
  "borderColor": "#dee2e6",
  "borderWidth": 1,
  "enableRounded": true,
  "roundedSize": 6
} /-->
```

This configuration will automatically display all authors, allowing you to filter by department using custom code.

### Dynamic Author Carousel

#### Recent Contributors Carousel

```php
// Display recently active authors
$recent_authors = get_users(array(
    'number' => 8,
    'orderby' => 'post_count',
    'order' => 'DESC',
    'has_published_posts' => true
));

$author_ids = wp_list_pluck($recent_authors, 'ID');

echo author_profile_blocks_render_block('author-carousel', array(
    'selectedAuthors' => $author_ids,
    'slidesToShow' => 3,
    'autoplay' => true,
    'autoplaySpeed' => 5000,
    'showDots' => true,
    'showArrows' => true,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => false,
    'showSocial' => true,
    'layout' => 'compact',
    'enableShadow' => true,
    'enableRounded' => true
));
```

#### Featured Authors by Category

```php
// Get authors who have written in specific categories
$category_authors = get_users(array(
    'number' => 6,
    'meta_query' => array(
        array(
            'key' => 'featured_author',
            'value' => '1',
            'compare' => '='
        )
    )
));

$author_ids = wp_list_pluck($category_authors, 'ID');

echo author_profile_blocks_render_block('author-carousel', array(
    'selectedAuthors' => $author_ids,
    'slidesToShow' => 2,
    'autoplay' => false,
    'showDots' => true,
    'showArrows' => true,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => true,
    'showSocial' => true,
    'layout' => 'centered',
    'enableShadow' => true,
    'enableRounded' => true,
    'textAlign' => 'center'
));
```

### Custom Carousel Themes

#### Dark Theme Carousel

```css
/* Dark theme for carousel */
.dark-carousel .wp-block-author-profile-blocks-author-carousel {
	background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
	color: #ecf0f1;
	padding: 40px 20px;
	border-radius: 15px;
}

.dark-carousel .apb-author-carousel-item {
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #ecf0f1;
}

.dark-carousel .apb-author-name {
	color: #ffffff;
}

.dark-carousel .apb-author-position {
	color: #bdc3c7;
}

.dark-carousel .slick-dots button {
	background: rgba(255, 255, 255, 0.3);
}

.dark-carousel .slick-dots .slick-active button {
	background: #3498db;
}
```

#### Gradient Overlay Effect

```css
/* Gradient overlay effect */
.gradient-carousel .apb-author-carousel-item {
	position: relative;
	overflow: hidden;
}

.gradient-carousel .apb-author-carousel-item::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: linear-gradient(
		45deg,
		rgba(52, 152, 219, 0.1) 0%,
		rgba(155, 89, 182, 0.1) 100%
	);
	z-index: 1;
	pointer-events: none;
}

.gradient-carousel .apb-author-carousel-item > * {
	position: relative;
	z-index: 2;
}
```

### Advanced Integration Examples

#### ACF Integration for Carousel Data

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('get_field')) {
        // Add presentation order
        $author_data['presentation_order'] = get_field('presentation_order', 'user_' . $user->ID) ?: 999;

        // Add featured status
        $author_data['is_featured'] = get_field('is_featured_speaker', 'user_' . $user->ID);

        // Add custom bio for carousel
        $carousel_bio = get_field('carousel_bio', 'user_' . $user->ID);
        if ($carousel_bio) {
            $author_data['description'] = $carousel_bio;
        }

        // Add session time
        $author_data['session_time'] = get_field('session_time', 'user_' . $user->ID);
    }

    return $author_data;
}, 10, 2);

// Sort featured authors first
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    usort($authors, function($a, $b) {
        $featured_a = get_user_meta($a['id'], 'is_featured_speaker', true) ? 1 : 0;
        $featured_b = get_user_meta($b['id'], 'is_featured_speaker', true) ? 1 : 0;

        if ($featured_a !== $featured_b) {
            return $featured_b - $featured_a; // Featured first
        }

        // Then sort by presentation order
        $order_a = get_user_meta($a['id'], 'presentation_order', true) ?: 999;
        $order_b = get_user_meta($b['id'], 'presentation_order', true) ?: 999;

        return $order_a - $order_b;
    });

    return $authors;
}, 10, 2);
```

#### Event-Specific Author Display

```php
add_filter('author_profile_blocks_rendered_author_carousel', function($content, $block) {
    // Add session time display for conference carousels
    if (isset($block['showSessionTime']) && $block['showSessionTime']) {
        $content = preg_replace_callback(
            '/data-author-id="(\d+)"/',
            function($matches) {
                $user_id = $matches[1];
                $session_time = get_user_meta($user_id, 'session_time', true);

                if ($session_time) {
                    $time_display = '<div class="session-time">' . esc_html($session_time) . '</div>';
                    return $matches[0] . ' data-session-time="' . esc_attr($session_time) . '"';
                }

                return $matches[0];
            },
            $content
        );
    }

    return $content;
}, 10, 2);
```

### Performance Optimization

#### Lazy Loading for Carousel Images

```php
add_filter('author_profile_blocks_rendered_author_carousel', function($content, $block) {
    // Add lazy loading to carousel images
    $content = str_replace(
        '<img class="apb-author-image"',
        '<img class="apb-author-image" loading="lazy"',
        $content
    );

    return $content;
}, 10, 2);
```

#### Carousel Preloading Strategy

```php
add_action('wp_enqueue_scripts', function() {
    if (has_block('author-profile-blocks/author-carousel')) {
        // Preload Slick Carousel CSS and JS
        wp_enqueue_style('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
        wp_enqueue_script('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
    }
});
```

### Accessibility Enhancements

#### Enhanced Keyboard Navigation

```javascript
// Enhanced carousel accessibility
jQuery(document).ready(function ($) {
	$(".wp-block-author-profile-blocks-author-carousel").each(function () {
		const $carousel = $(this);

		// Add ARIA labels
		$carousel.find(".slick-prev").attr("aria-label", "Previous authors");
		$carousel.find(".slick-next").attr("aria-label", "Next authors");

		// Enhance dot navigation
		$carousel.find(".slick-dots button").each(function (index) {
			$(this).attr("aria-label", `Go to author slide ${index + 1}`);
		});

		// Pause on focus
		$carousel.on("focusin", function () {
			if ($(this).hasClass("slick-initialized")) {
				$(this).slick("slickPause");
			}
		});

		$carousel.on("focusout", function () {
			if ($(this).hasClass("slick-initialized")) {
				$(this).slick("slickPlay");
			}
		});
	});
});
```

#### Custom Carousel Controls

```php
add_filter('author_profile_blocks_rendered_author_carousel', function($content, $block) {
    // Add custom play/pause controls
    if (isset($block['autoplay']) && $block['autoplay']) {
        $play_pause_button = '<button class="carousel-play-pause" aria-label="Pause carousel autoplay">⏸️</button>';
        $content = str_replace('</div></div></div>', $play_pause_button . '</div></div></div>', $content);
    }

    return $content;
}, 10, 2);
```

## Tips and Best Practices

- For best results, use a consistent image size for all author profile pictures
- Keep descriptions concise to maintain visual consistency between slides
- For mobile-friendly carousels, ensure content is readable when reduced to a single slide
- Enable autoplay for home pages or hero sections to draw attention
- Disable autoplay for more in-depth content to allow users to read at their own pace
- Use arrows for desktop and dots for mobile navigation
- 3 slides works well for standard width content areas
- 1-2 slides is ideal for sidebar or narrower areas

## Carousel Navigation

The Author Carousel Block provides two navigation options for users to browse through authors:

### Navigation Arrows

Navigation arrows appear on the left and right sides of the carousel, allowing users to move to the previous or next slide. These can be:

- Enabled/disabled in the block settings
- Styled using custom CSS
- Hidden on mobile devices automatically

### Navigation Dots

Navigation dots appear below the carousel, indicating:

1. The total number of slides
2. The current active slide
3. Clickable navigation to any specific slide

These dots can be enabled/disabled in the block settings.

## Technical Notes

- The carousel uses the Slick Carousel jQuery plugin for smooth, responsive functionality
- All carousel settings are fully responsive and mobile-friendly
- Scripts are loaded only when the block is used to maintain site performance

## Related Blocks

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) - For displaying a single author in detail
- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) - For displaying multiple authors in a grid layout
- [Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %}) - For displaying multiple authors in a list format
