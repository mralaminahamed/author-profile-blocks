---
layout: default
title: Author Carousel Block
parent: Blocks
nav_order: 3
permalink: /blocks/author-carousel/
---

# Author Carousel Block
{: .no_toc }

Showcase multiple authors in an interactive sliding carousel powered by Slick Carousel.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Overview

The Author Carousel Block renders a horizontally-sliding carousel using [Slick Carousel](https://kenwheeler.github.io/slick/) (jQuery-based). It's ideal for:

- Featured team members on home pages
- Highlighting contributors in a space-efficient way
- Showcasing speakers or panelists
- Creating engaging team presentations

---

## Block Settings

### Carousel Settings

| Setting | Default | Description |
|---|---|---|
| Slides to Show | 3 | Slides visible at once (1–5) |
| Autoplay | On | Auto-advance slides |
| Autoplay Speed | 3000 ms | Time between slides |
| Show Dots | On | Navigation dot indicators |
| Show Arrows | On | Prev/Next arrow buttons |
| Infinite Loop | On | Wrap around at end |
| Maximum Authors | 10 | Cap on total authors shown |
| Filter by Role | All | Limit to a specific WordPress role |

### Display Settings

| Setting | Default |
|---|---|
| Show Author Image | On |
| Show Author Position | On |
| Show Author Email | On |
| Show Author Description | On |
| Show Member Since Date | On |
| Show Social Links | On |

### Style Settings

- **Item Padding** — space inside each slide card (px)
- **Enable Shadow** — subtle drop shadow on each card
- **Enable Rounded Corners** — border-radius on cards
- **Enable Border** — border around each card
- **Background Color** — card background color
- **Text Alignment** — left / center / right

---

## Layouts

### Card

Author image at the top, name and position below, then description and social links. Best for formal team showcases.

### Compact

Image on the left, name and position to the right. More space-efficient per slide.

### Centered

All content centered — avatar, name, position, description, social icons. Great for spotlight carousels.

---

## Responsive Breakpoints

The carousel adjusts automatically:

| Viewport | Slides Shown |
|---|---|
| ≥ 992 px | As configured (default 3) |
| 768–991 px | 2 |
| < 576 px | 1 (arrows hidden) |

---

## How the Carousel Works

The block uses server-side rendering (PHP `render_callback`). On the frontend:

1. `view.js` imports jQuery + Slick Carousel + both `slick.css` and `slick-theme.css`
2. On DOM ready, `$('.apbl-author-carousel').each(...)` initializes Slick with merged default + block settings
3. Settings from the editor are passed via the `data-settings` attribute on `.apbl-author-carousel`

The bundled CSS is registered as `viewStyle: file:./view.css` in `block.json` so it's enqueued automatically.

---

## CSS Classes

```css
/* Block root */
.wp-block-author-profile-blocks-author-carousel { }

/* Slick-initialized carousel */
.apbl-author-carousel { }

/* Individual slide */
.apbl-author-carousel-item { }

/* Slick-generated nav */
.apbl-author-carousel .slick-dots { }
.apbl-author-carousel .slick-arrow { }
.apbl-author-carousel .slick-prev { }
.apbl-author-carousel .slick-next { }
```

---

## Block Markup Example

```html
<!-- wp:author-profile-blocks/author-carousel {
  "slidesToShow": 3,
  "autoplay": true,
  "autoplaySpeed": 3000,
  "showDots": true,
  "showArrows": true,
  "infinite": true,
  "showImage": true,
  "showPosition": true,
  "showDescription": true,
  "showSocial": true,
  "layout": "card",
  "textAlign": "center"
} /-->
```

---

## Tips

- 3 slides works well on desktop; the block automatically collapses to 1 on mobile
- Use **Centered** layout with `textAlign: center` for the most polished look
- Keep descriptions brief — card heights equalize across slides only when content length is similar
- Autoplay with arrows and dots gives users manual control even during auto-advance

---

## Related Blocks

- [Author Profile Block]({{ site.baseurl }}{% link blocks/author-profile.md %}) — single author detail
- [Author Grid Block]({{ site.baseurl }}{% link blocks/author-grid.md %}) — static multi-author grid
- [Author List Block]({{ site.baseurl }}{% link blocks/author-list.md %}) — vertical author directory
