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

![Author Carousel Block](../../assets/images/author-carousel-block.png)

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

![Card Layout](../../assets/images/author-carousel-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:
- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal when space is limited.

![Compact Layout](../../assets/images/author-carousel-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:
- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for highlighting featured authors.

![Centered Layout](../../assets/images/author-carousel-centered-layout.png)

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