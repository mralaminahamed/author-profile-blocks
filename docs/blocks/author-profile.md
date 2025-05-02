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

![Author Profile Block](../../assets/images/author-profile-block.png)

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

![Card Layout](../../assets/images/author-profile-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:
- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal for sidebar widgets or when space is limited.

![Compact Layout](../../assets/images/author-profile-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:
- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for author spotlights or featured authors.

![Centered Layout](../../assets/images/author-profile-centered-layout.png)

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