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

## Version 1.0.4 (May 2026)

### Bug Fixes

- **Blocks blank on frontend** — `render_error_message()` now returns `''` outside the editor instead of leaking an error div to site visitors
- **Carousel broken on classic themes** — `author-carousel-view.js` now declares `jquery` as a dependency so Slick initializes correctly on Astra, Blocksy, and similar themes
- **Production zip missing vendor autoloader** — removed `vendor` from `.distignore` so the Composer classmap ships with the plugin (root cause of "block does not appear" on fresh installs from WordPress.org)
- **CSS color/length injection** — all color attributes now pass through `sanitize_css_color()` (hex, rgba, hsl, keywords) and length attributes through `sanitize_css_length()`
- **Dead CSS variable block** — removed duplicate `--author-custom-var-1/2` assignment that targeted variables never referenced in SCSS or JS
- **Template arbitrary file inclusion** — `get_template()` now validates the filtered path is within the plugin, active theme, or child theme directory before including
- **Raw `$_POST` passed to action** — `do_action('author_profile_blocks_save_profile_fields')` no longer passes raw `$_POST` as a second argument
- **`extract()` in templates** — replaced with an explicit `foreach` loop to satisfy the WordPress.org plugin review checker
- **Registration date wrong timezone** — `get_registered_date()` and `get_author_data()` now use `mysql2date()` which correctly converts the UTC-stored value to the site's configured timezone

### Tests

- PHP integration suite updated: all error-path tests now explicitly call `simulate_editor_context()` (328 tests, 876 assertions — all green)
- New e2e spec `error-message-visibility.spec.ts` covering frontend-blank and editor-placeholder scenarios for all four blocks

---

## Version 1.0.3 (May 2026)

### Changed

- Split `AuthorBlockBase` god-class (1453 lines) into 7 focused traits under `includes/Blocks/Concerns/`
- Promoted 5 inspector components to shared location `src/supports/js/components/inspector/` — net delete ~1300 lines of duplicated TSX
- Extracted `loadGoogleFont` into shared `useGoogleFont` hook
- Migrated admin menu from sub-page under Settings to top-level
- Redesigned editor placeholder UI with shared `AuthorBlockPlaceholder` component

### Bug Fixes

- Author profile block missing `save: () => null` (silent default-save bug)
- Duplicate `useAuthors` hooks with conflicting return shapes
- Stale `displayStyle` → `is-style-*` class emission conflicting with `layoutPreset`
- Carousel single-slide crash in Slick's `initADA` (skip init when ≤ 1 slide)
- `AuthorsListPreview` data shape mismatch: now reads `avatar_url` / `author_position` from REST response
- PHPCS clean for wp.org plugin review standard

---

## Version 1.0.2 (May 2026)

### Added

- React + shadcn/ui admin SPA replacing legacy PHP settings page
- REST API endpoint `/v1/settings` for plugin configuration
- Indigo (`#4f46e5`) brand color system across admin and blocks

### Changed

- TypeScript migration for all JS files in `src/blocks/` and `src/admin/`
- Migrated Tailwind v3 → v4 with CSS variable-based theming (`@theme inline`)
- Reorganized `src/` into `src/admin/`, `src/blocks/`, `src/supports/`

---

## Version 1.0.0 (April 2025)

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
