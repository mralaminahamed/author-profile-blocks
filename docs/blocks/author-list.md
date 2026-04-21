---
layout: default
title: Author List Block
parent: Blocks
nav_order: 4
permalink: /blocks/author-list/
---

# Author List Block
{: .no_toc }

Display multiple authors in a vertical list — perfect for team directories and contributor pages.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Overview

The Author List Block renders authors in a flex column with consistent gap between items. It's perfect for:

- Team directories
- Contributor pages
- Faculty or staff listings
- Event speaker rosters

---

## Block Settings

### Author Selection

- **Author Selection** — choose specific users from the author picker
- **Filter by Role** — optionally limit to: All, Administrator, Editor, Author, Contributor
- **Maximum Authors** — cap total displayed (0 = show all selected)

### List Settings

| Setting | Options |
|---|---|
| Display Style | Compact, Detailed |
| Show Dividers | on/off |
| Rounded Corners | on/off |
| Hover Effect | on/off |

### Display Elements

- Show Author Image
- Show Position / Role
- Show Email
- Show Description
- Show Social Links

---

## Display Styles

### Compact

```
[ avatar ] Name          @position
           [social icons]
```

Image on the left, name and position to the right, social links end-aligned. Space-efficient for long lists.

### Detailed

```
[ avatar ] Name
           position
           email@example.com
           Bio text goes here across multiple lines...
           [social icons]
```

Full details — image left, all content right. Best for team directory pages where each author deserves a full read.

---

## Style Presets

Applied as a modifier class on the block root:

| Preset | Effect |
|---|---|
| `is-style-card` | White cards with left accent line on hover |
| `is-style-minimal` | No background, bottom border only, last item borderless |
| `is-style-bordered` | 2px border, hover highlights in indigo |
| `is-style-shadow` | Drop shadow cards that lift on hover |
| `is-style-alternating` | Even items get a light indigo tint |

---

## CSS Classes

```css
/* Block root */
.wp-block-author-profile-blocks-author-list { }

/* List container (<ul>) */
.apbl-author-list-items { }

/* Individual row */
.apbl-author-list-item { }

/* Layout-specific inner wrappers */
.apbl-author-compact { }      /* compact display */
.apbl-author-detailed { }     /* detailed display */

/* Avatar */
.apbl-author-image { }

/* Content elements */
.apbl-author-name { }
.apbl-author-position { }
.apbl-author-email { }
.apbl-author-description { }
.apbl-social-profiles { }
.apbl-social-list { }

/* State modifiers */
.is-rounded { }
.has-hover-effect { }
.has-dividers { }
```

---

## Block Markup Examples

### Team Directory (Detailed)

```html
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "detailed",
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "enableDividers": true,
  "enableRounded": true
} /-->
```

### Contributors List (Compact)

```html
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8],
  "displayStyle": "compact",
  "showImage": true,
  "showPosition": true,
  "showSocial": true,
  "enableDividers": true,
  "enableHoverEffect": true
} /-->
```

---

## Responsive Behavior

- **Desktop / Tablet** — compact and detailed display as configured
- **Mobile (< 576 px)** — detailed layout switches to stacked (image above content)

---

## Tips

- **Compact** style works best for 6+ authors
- **Detailed** style works best when bios are important (about pages, faculty directories)
- Keep descriptions to 2 sentences in Detailed style to maintain consistent row height
- Enable hover effect for interactive directories
- Use `is-style-card` preset for a polished editorial look out of the box

---

## Related Blocks

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) — single author detail
- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) — responsive multi-column grid
- [Author Carousel Block]({{ site.baseurl }}{% link blocks/author-carousel.md %}) — horizontal sliding carousel
