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

![Author Grid Block](../../assets/images/author-grid-block.png)

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

![Card Layout](../../assets/images/author-grid-card-layout.png)

### Compact Layout

The Compact layout provides a more space-efficient presentation:
- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal when displaying many authors.

![Compact Layout](../../assets/images/author-grid-compact-layout.png)

### Centered Layout

The Centered layout focuses on symmetry and balance:
- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for highlighting featured team members.

![Centered Layout](../../assets/images/author-grid-centered-layout.png)

## Responsive Behavior

The Author Grid automatically adjusts based on screen size:

- **Desktop**: Displays the number of columns specified (1-4)
- **Tablet**: Automatically reduces to 2 columns
- **Mobile**: Switches to a single column for optimal mobile viewing

## Usage Examples

### Team Page (3 Columns)

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

### Contributor List (4 Columns, Compact)

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

![Author Picker](../../assets/images/author-picker.png)

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