---
layout: default
title: Customization
nav_order: 5
permalink: /customization/
---

# Customization
{: .no_toc }

Learn how to customize the appearance and behavior of the Author Profile Blocks to match your website's design.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Using Block Settings

Each block includes comprehensive settings accessible in the block sidebar when a block is selected.

### Common Style Options

All blocks share these style options:

- **Background Color** — Set custom background colors for the block or individual items
- **Text Alignment** — Align text left, center, or right
- **Padding** — Adjust spacing around and within content
- **Borders** — Add, style, and colorize borders
- **Rounded Corners** — Add rounded corners to blocks or items
- **Shadow Effects** — Apply subtle shadow effects
- **Animations** — Choose entry animations (fadeIn, slideUp, scaleIn, bounce, etc.)
- **Hover Effects** — Add lift, glow, scale, rotate, or shadow on hover

### Block-Specific Options

- **Author Profile Block** — Layout (card / compact / centered), "More Content" section, avatar alignment
- **Author Grid Block** — Columns (1–4), item spacing, layout preset
- **Author Carousel Block** — Slides to show, autoplay speed, dots, arrows, infinite loop
- **Author List Block** — Display style (compact / detailed), dividers, hover effect

---

## CSS Class Reference

All CSS classes use the `apbl-` prefix. Use these in your theme's stylesheet or the WordPress **Additional CSS** panel.

### Global Classes

```css
/* Block root wrappers */
.wp-block-author-profile-blocks-author-profile { }
.wp-block-author-profile-blocks-author-grid { }
.wp-block-author-profile-blocks-author-carousel { }
.wp-block-author-profile-blocks-author-list { }

/* Shared element classes */
.apbl-author-image { }      /* avatar wrapper */
.apbl-author-name { }       /* heading */
.apbl-author-position { }   /* job title / role badge */
.apbl-author-email { }      /* email link */
.apbl-author-description { } /* bio text */
.apbl-social-profiles { }   /* social icon row */
.apbl-social-list { }       /* <ul> inside social-profiles */
```

### Author Grid Block

```css
.apbl-author-grid { }              /* grid container */
.apbl-author-grid-item { }         /* individual card */

/* Column variants */
.apbl-columns-1 .apbl-author-grid-item { }
.apbl-columns-2 .apbl-author-grid-item { }
.apbl-columns-3 .apbl-author-grid-item { }
.apbl-columns-4 .apbl-author-grid-item { }
```

### Author Carousel Block

```css
.apbl-author-carousel { }          /* Slick-initialized carousel root */
.apbl-author-carousel-item { }     /* individual slide wrapper */

/* Slick overrides */
.apbl-author-carousel .slick-dots { }
.apbl-author-carousel .slick-arrow { }
.apbl-author-carousel .slick-prev { }
.apbl-author-carousel .slick-next { }
```

### Author List Block

```css
.apbl-author-list { }              /* list root */
.apbl-author-list-items { }        /* <ul> flex column */
.apbl-author-list-item { }         /* individual row */
.apbl-author-compact { }           /* compact layout inner */
.apbl-author-detailed { }          /* detailed layout inner */
.apbl-author-left { }              /* avatar column */
.apbl-author-right { }             /* content column */
```

### Style Preset Modifiers

```css
/* Applied to the block root */
.is-style-card { }
.is-style-minimal { }
.is-style-bordered { }
.is-style-shadow { }
.is-style-alternating { }

/* State modifiers */
.is-rounded { }
.has-shadow { }
.has-border { }
.has-hover-effect { }
.has-dividers { }
```

### Animation Classes

```css
.has-fadeIn-animation { }
.has-slideUp-animation { }
.has-slideDown-animation { }
.has-slideLeft-animation { }
.has-slideRight-animation { }
.has-scaleIn-animation { }
.has-bounce-animation { }
```

### Hover Effect Classes

```css
.has-lift-hover:hover { }
.has-glow-hover:hover { }
.has-scale-hover:hover { }
.has-rotate-hover:hover { }
.has-shadow-hover:hover { }
```

---

## CSS Custom Properties

The plugin uses CSS custom properties for runtime theming. Override them on the block root or globally:

```css
/* Avatar */
--author-avatar-size: 96px;
--author-avatar-justify: flex-start; /* flex-start | center | flex-end */

/* Animations */
--author-animation-duration: 400ms;

/* Google Font family (when googleFont attribute is set) */
--google-font-family: 'Merriweather', serif;

/* Gradient (when gradientBackground is enabled) */
--gradient-direction: to bottom;
--gradient-start-color: #ffffff;
--gradient-end-color: #f8f9fa;

/* Box shadow (when boxShadow is enabled) */
--box-shadow-color: rgba(0,0,0,0.15);
--box-shadow-blur: 16px;
--box-shadow-spread: 0px;
--box-shadow-horizontal: 0px;
--box-shadow-vertical: 4px;

/* Border (when enableBorder is enabled) */
--border-width: 1px;
--border-color: #e0e0e0;
--border-radius: 0px;

/* Container width override */
--author-list-container-width: 900px;
```

### Example: change avatar size sitewide

```css
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid {
    --author-avatar-size: 120px;
}
```

---

## Color Customization

### Matching the Indigo Design System

The plugin ships with an indigo design palette. Override to match your brand:

```css
/* Re-theme with a teal palette */
.wp-block-author-profile-blocks-author-profile,
.wp-block-author-profile-blocks-author-grid,
.wp-block-author-profile-blocks-author-carousel,
.wp-block-author-profile-blocks-author-list {
    --apbl-primary: #0d9488;      /* teal-600 */
    --apbl-primary-mid: #99f6e4;  /* teal-200 */
    --apbl-primary-light: #f0fdfa; /* teal-50 */
}
```

---

## Carousel Customization

The carousel uses [Slick Carousel](https://kenwheeler.github.io/slick/). Override Slick's generated classes:

```css
/* Custom dot style */
.apbl-author-carousel .slick-dots li button:before {
    color: #4f46e5;
    font-size: 10px;
}

/* Custom arrow buttons */
.apbl-author-carousel .slick-arrow {
    background: #4f46e5;
    border-radius: 50%;
    width: 40px;
    height: 40px;
}
.apbl-author-carousel .slick-arrow:hover {
    background: #3730a3;
}
```

Pass custom Slick settings via the `data-settings` attribute on `.apbl-author-carousel` (set server-side by the block's PHP render callback).

---

## Typography

```css
/* Author name */
.apbl-author-name {
    font-family: 'Georgia', serif;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: -0.02em;
}

/* Position badge */
.apbl-author-position {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 600;
}

/* Bio text */
.apbl-author-description {
    font-size: 0.95rem;
    line-height: 1.65;
}
```

---

## Responsive Overrides

```css
/* Tablet */
@media (max-width: 768px) {
    .apbl-author-grid-item { padding: 1rem; }
    .apbl-author-description { font-size: 0.875rem; }
}

/* Mobile */
@media (max-width: 480px) {
    .apbl-author-detailed { flex-direction: column; }
    --author-avatar-size: 72px;
}
```

---

## PHP Filter Hooks

### Modify author data

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    // Swap Gravatar for an ACF image field
    $image_id = get_field( 'profile_image', 'user_' . $user->ID );
    if ( $image_id ) {
        $author_data['image'] = wp_get_attachment_image_url( $image_id, 'medium' );
    }
    return $author_data;
}, 10, 2 );
```

### Modify the authors query

```php
add_filter( 'author_profile_blocks_author_query_args', function( $args ) {
    $args['orderby'] = 'registered';
    $args['order']   = 'DESC';
    return $args;
} );
```

### Filter rendered block output

```php
add_filter(
    'render_block_author-profile-blocks/author-profile',
    function( $content, $block ) {
        // wrap in custom container
        return '<div class="my-wrapper">' . $content . '</div>';
    },
    10, 2
);
```

---

## Theme Integration

### WordPress theme.json palette

```json
{
    "settings": {
        "color": {
            "palette": [
                { "slug": "indigo", "color": "#4f46e5", "name": "Indigo" }
            ]
        }
    }
}
```

The blocks inherit `--wp--preset--color--*` variables automatically when using block color controls.

### Twenty Twenty-Five

```css
.apbl-author-name {
    font-family: var(--wp--preset--font-family--heading);
}
.apbl-author-description {
    font-size: var(--wp--preset--font-size--small);
}
```

---

## Accessibility

The blocks output semantic HTML. To further enhance keyboard / screen-reader experience:

```css
/* Visible focus ring on social links */
.apbl-social-profiles a:focus-visible {
    outline: 2px solid #4f46e5;
    outline-offset: 3px;
    border-radius: 4px;
}
```
