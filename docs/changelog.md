---
layout: default
title: Changelog
nav_order: 9
permalink: /changelog/
---

# Changelog
{: .no_toc }

A record of all notable changes made to the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Version 1.0.1 (April 2026)

### New Features

- **Indigo editorial design system** — cohesive `#4f46e5` primary palette with accent animations across all 4 blocks
- **Lucide React SVG icons** — replaced emoji and Dashicons with crisp Lucide icons in all editor controls
- **Functional carousel** — Slick Carousel now initializes correctly with responsive breakpoints, autoplay, arrow icons, and dot navigation
- **Author image alignment** — avatar now honors text-alignment via CSS custom property `--author-avatar-justify` (flex-based)
- **List item gap** — consistent spacing between items in the Author List block

### Bug Fixes

- Fixed fatal `TypeError` when `apbl_social_profiles` user meta contains a non-array value
- Fixed carousel selector mismatch (`apbl-` prefix inconsistency between PHP output and JS)
- Fixed Slick CSS never loading on the frontend (missing `viewStyle` registration in `block.json`)
- Fixed Slick arrow icons showing as "Previous"/"Next" text (missing `slick-theme.css` import)
- Completed migration of all CSS class names to `apbl-` prefix throughout templates and SCSS

### Developer Improvements

- Modern SCSS module system: `@use`, `@forward`, `sass:color`, `sass:math`; removed all `@extend` and deprecated `lighten()`/`darken()` calls
- Webpack bundles Slick CSS into `view.css` via `viewStyle` field in `block.json`
- Added `.distignore`, updated `.gitignore` and `.gitattributes` for cleaner releases

---

## Version 1.0.0 (February 2026)

### Initial Release

- **Author Profile Block** — single-author display with card, compact, and centered layouts
- **Author Grid Block** — multi-author responsive grid (1–4 columns) with author picker and role filter
- **Author List Block** — vertical author directory with compact and detailed display styles
- **Author Carousel Block** — sliding carousel powered by Slick Carousel with autoplay, dots, and arrows
- Extended user profile fields: position/title (`apbl_author_position`), description (`apbl_author_description`), social profiles (`apbl_social_profiles`)
- Social platform links: Facebook, Twitter, LinkedIn, Instagram, personal website
- Server-side rendering via `render_callback` for all blocks
- Animation system: fadeIn, slideUp, slideDown, slideLeft, slideRight, scaleIn, bounce
- Hover effects: lift, glow, scale, rotate, shadow
- Style presets per block: card, minimal, bordered, shadow, alternating
- CSS custom properties for runtime theming
- Responsive design with mobile-first breakpoints
- WordPress 6.0+ and PHP 7.4+ compatibility
