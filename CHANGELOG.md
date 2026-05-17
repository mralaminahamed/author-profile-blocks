# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2026-05-17

### Fixed

- Blocks blank on frontend when no author selected: `render_error_message()` now returns `''` outside editor context instead of leaking an error div to visitors
- Carousel broken on classic themes (Astra, Blocksy, etc.): `author-carousel-view.js` now declares `jquery` as a dependency so Slick initialises correctly
- Production zip missing `vendor/autoload.php`: removed `vendor` from `.distignore` so the Composer classmap autoloader ships with the plugin (root cause of "block does not appear" on fresh installs from WordPress.org)
- CSS color/length injection: all color attributes now pass through `sanitize_css_color()` (hex, rgba, hsl, keywords), length attributes through `sanitize_css_length()`, and gradient direction through an allowlist
- Dead CSS variable block: removed duplicate `--author-custom-var-1/2` assignment that targeted a CSS variable name never referenced in SCSS or JS
- Template arbitrary file inclusion: `get_template()` now validates the filtered path is within the plugin, active theme, or child theme directory before `include`
- `do_action('author_profile_blocks_save_profile_fields')` no longer passes raw `$_POST` as second argument
- `extract()` in `get_template()` replaced with explicit `foreach` loop to satisfy WordPress.org plugin review checker
- Registration date displayed in wrong timezone: replaced `strtotime()` + `date_i18n()` with `mysql2date()` which correctly converts the UTC-stored value to the site's configured timezone

### Tests

- PHP integration suite updated for new `render_error_message()` behaviour: all error-path tests now explicitly call `simulate_editor_context()` (328 tests, 876 assertions — all green)
- New e2e spec `error-message-visibility.spec.ts` covering frontend-blank and editor-placeholder scenarios for all four blocks
- TypeScript config: fixed TS5107/TS5101 deprecation errors, added `@types/node`, type stub for `dotenv/config`

## [1.0.3] - 2026-05-12

### Changed

- Split `Author_Block_Base` god-class (1453 lines) into 7 focused traits under `includes/Blocks/Concerns/`
- Promoted 5 inspector components to shared location `src/supports/js/components/inspector/` (AdvancedStyling, AdvancedTypography, AnimationControls, LayoutPresets, PresetStyles) — net delete ~1300 lines of duplicated TSX
- Extracted `loadGoogleFont` into shared `useGoogleFont` hook
- Renamed all `Snake_Case_PascalCase` classes/traits to `PascalCase` per PSR-4 / warranty-cart reference style (15 renames)
- Modernized composer deps: `phpcompatibility/phpcompatibility-wp ^2.1`, `yoast/phpunit-polyfills ^4.0`, added `ergebnis/composer-normalize` + `phpstan/extension-installer`
- Migrated admin menu from sub-page under Settings to top-level (`toplevel_page_author-profile-blocks`)
- Aligned `supports.spacing.margin: true` across all 4 block.json files
- Editor placeholder UI redesigned with shared `AuthorBlockPlaceholder` (tinted bg, picker card, aligned select+button)

### Fixed

- Author profile block missing `save: () => null` (silent default-save bug)
- Duplicate `useAuthors` hooks with conflicting return shapes
- Dead `register_admin()` method + duplicate Admin/PluginLinks instantiation
- Stale `displayStyle` → `is-style-*` class emission conflicting with `layoutPreset`
- Carousel single-slide crash in slick's `initADA` (skip slick init when ≤1 slide)
- `AuthorsListPreview` data shape mismatch: read `avatar_url` / `author_position` instead of `avatar` / `position`
- Template guards added to `get_template` / `get_template_part` for missing files
- PHPCS clean for wp.org plugin review standard
- PHPUnit baseline: 5 errors + 7 failures → 0 (all 319 tests passing)

### Internal

- ESLint migrated to flat config (v10)
- PHPUnit migrated `<filter>/<whitelist>` to `<coverage>/<include>`
- Tests renamed `tests/php/src/unit/` → `Unit/` for PSR-4 compliance
- AGENTS.md synced with current `src/admin/`, `src/blocks/`, `src/supports/` layout

## [1.0.2] - 2026-05-11

### Added

- React + shadcn/ui admin SPA replacing legacy PHP settings page
- REST API endpoint `/v1/settings` for plugin configuration
- Indigo (`#4f46e5`) brand color system across admin and blocks
- TypeScript migration for all 72 JS files in `src/blocks/` and `src/admin/`

### Changed

- Migrated Tailwind v3 → v4 with CSS variable-based theming (`@theme inline`)
- Reorganized `src/` → `src/admin/`, `src/blocks/`, `src/supports/` (34 files)
- Collapsed admin tabs 5 → 3 (shadcn-driven)
- Migrated ESLint 8 → 10 flat config
- Renamed test dir `tests/php/src/unit/` → `Unit/` for PSR-4 compliance
- Block placeholder UI redesigned with tinted background, picker cards, and aligned controls

### Fixed

- PSR-4 autoload warnings for test classes
- Carousel/grid inspector layout and CLS issues (16 bugs)
- Placeholder button/select vertical alignment
- Apply Tailwind `apbl` prefix consistently across admin components

## [1.0.0] - 2025-04-20

### Added

- Initial release of Author Profile Blocks
- Author Profile Block - Display individual author profiles with customizable layouts
- Author Grid Block - Display multiple authors in a responsive grid layout
- Author List Block - Display authors in a clean list format
- Author Carousel Block - Interactive carousel for showcasing author profiles
- Gutenberg block-based architecture with block.json registration
- WordPress REST API integration for author data
- Responsive design with mobile-first approach
- Admin settings and configuration options
- Comprehensive block customization options (layout, styling, content)
- WordPress user integration for author data
- Block preview functionality in editor
- Accessibility features and keyboard navigation

### Changed

- Migrated from legacy shortcode system to modern Gutenberg blocks
- Updated build system to use WordPress Scripts
- Improved code organization with PSR-4 autoloading

### Technical Details

- PHP 7.4+ compatibility
- WordPress 6.0+ compatibility
- PSR-4 namespace: `AuthorProfileBlocks\`
- WordPress Scripts build system
- PHPCS code standards compliance
- PHPStan static analysis
- ESLint and Stylelint for JavaScript/CSS
- Responsive SCSS with variables and mixins