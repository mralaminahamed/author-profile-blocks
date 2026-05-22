=== Author Profile Blocks ===
Contributors:      mralaminahamed
Tags:              block, gutenberg, author, profile, team
Tested up to:      6.9
Stable tag:        1.1.0
Requires at least: 6.0
Requires PHP:      7.4
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Display author profiles and team members in Gutenberg blocks, shortcodes, and widgets — grid, carousel, list, and single profile.

== Description ==

**Author Profile Blocks** is a WordPress plugin for showcasing author profiles and team members using four Gutenberg blocks, four shortcodes, and a classic widget. It supports two data sources: standard WordPress Users and an optional Team Member custom post type (`apbl_team_member`).

=== Four Block Types ===

**Author Profile** — Single author showcase with full control over layout order, avatar shape, typography, and social links. Four content order variants: image-content, content-image, image-top, content-top.

**Author Grid** — Responsive grid of multiple authors. Supports 1–4 columns with five style presets: card, minimal, bordered, shadow, mosaic.

**Author List** — Vertical author list with three display styles: compact (image + summary), detailed (full two-column layout), and minimal (name + position only). Supports ordered or unordered lists.

**Author Carousel** — Interactive slider built on Slick. Seven style presets including modern cards, classic carousel, elegant profile, and creative gradient layout. Configurable slides, autoplay, dots, and arrows.

=== Shortcodes ===

Four shortcodes are available for classic themes or page builders:

* `[apbl_profile]` — single author card
* `[apbl_grid]` — responsive author grid
* `[apbl_list]` — author list
* `[apbl_carousel]` — author carousel with autoplay

All shortcodes accept `id`, `source` (user or team_member), `style`, `number`, and display toggle attributes.

=== Classic Widget ===

The **Author Profile Widget** lets you add a single author card to any widget area. Choose the author, display style, and whether to show social links and bio.

=== Team Member CPT ===

Activate the built-in `apbl_team_member` custom post type to manage team members separately from WordPress users. Supports title, bio, featured image, and menu order. Each team member has position and social profile meta fields. Organise members by department using the hierarchical `apbl_department` taxonomy.

=== Design System ===

Every block ships with a refined indigo editorial design — distinctive typography, layered shadows, smooth cubic-bezier transitions, and an indigo accent system with animated hover reveals. All visual properties are overridable via CSS custom properties.

=== Customization Options ===

* **Avatar**: circle, square, rounded, or custom border-radius; configurable size and border
* **Typography**: name size, weight, color, alignment, letter-spacing; position shown as uppercase indigo badge
* **Layout presets**: choose from registered style variations (card, minimal, bordered, shadow, and more) per block
* **Animations**: 7 entrance animation types — fadeIn, slideUp, slideDown, slideLeft, slideRight, scaleIn, bounce
* **Hover effects**: lift, glow, scale, rotate, shadow — applied to items or the block wrapper
* **Social icons**: square pill shape with indigo fill on hover; supports Facebook, Twitter/X, LinkedIn, Instagram, website
* **Colors**: background, border, text, gradient — all configurable via the inspector
* **Spacing**: section spacing, padding, item spacing, container width
* **Advanced**: CSS filters (brightness, contrast, saturation), transform (scale, rotate), box shadow, custom CSS class, Google Font support

=== User Profile Extensions ===

The plugin adds extra fields to the standard WordPress user profile screen:

* Position / job title
* Extended bio
* Social media URLs: Facebook, Twitter/X, LinkedIn, Instagram, personal website
* Custom "Member since" label
* Department
* Skills
* Location
* Phone
* Availability
* Website label

=== Performance ===

* Server-side rendering with PHP template caching
* Configurable cache duration
* Lazy loading for author avatars
* No unnecessary JavaScript on the frontend

=== Developer-Friendly ===

* Clean PHP template hierarchy — override any template from your theme
* Extensive CSS custom properties for runtime styling
* WordPress filter hooks on every rendered block and shortcode
* Modern SCSS source with `@use`/`@forward` module system and `sass:color` functions
* `apbl-` namespaced CSS classes throughout
* PSR-4 autoloaded, WordPress Coding Standards compliant

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install via **Plugins → Add New**.
2. Activate the plugin.
3. Go to **Users → Your Profile** (or any user's profile) to add author information.
4. Optionally go to **Team Members** in the admin menu to add team members.
5. Open any page or post in the Gutenberg editor and search for "author" to find the blocks.

== Frequently Asked Questions ==

= Does this plugin create a custom post type? =

Yes, optionally. The plugin registers an `apbl_team_member` custom post type alongside standard WordPress Users. You can use either source (or both) in any block or shortcode via the `source` attribute. If you only want to display WordPress users, ignore the Team Members menu entirely.

= How do I add author data like position or social links? =

For WordPress Users, edit any user profile under **Users → All Users → Edit**. The plugin adds a dedicated "Author Profile" section.

For Team Members, go to **Team Members → Add New** in the admin menu.

= Can I use shortcodes instead of blocks? =

Yes. Four shortcodes are available for classic themes or page builders: `[apbl_profile]`, `[apbl_grid]`, `[apbl_list]`, and `[apbl_carousel]`. Each accepts the same source, style, and display options as the corresponding block.

= Can I override the block templates? =

Yes. Copy any file from `wp-content/plugins/author-profile-blocks/templates/` into `wp-content/themes/your-theme/author-profile-blocks/` and the plugin will use your version instead.

= How do I change block styles? =

Each block exposes a full Style panel in the block sidebar. For deeper overrides, all CSS uses `apbl-` prefixed classes and `--author-*` CSS custom properties, making targeted overrides straightforward.

= Can I show only authors with a specific role? =

Yes. The Grid, List, and Carousel blocks and their shortcode equivalents include a role filter. Set it to any registered WordPress role and only users with that role will be displayed.

= Can I show only team members from a specific department? =

Yes. The `apbl_department` taxonomy is hierarchical. Use the department filter in the block inspector or shortcode `department` attribute to narrow results.

= Is the plugin compatible with full-site editing (FSE) themes? =

Yes. The blocks render server-side and use standard Gutenberg wrapper attributes, so they work in both classic and FSE/block themes.

= Will it slow down my site? =

Each block caches its rendered HTML. On repeat page loads, output is served from cache rather than re-querying the database and re-rendering PHP templates.

= Is the plugin translation-ready? =

Yes. All user-facing strings use `__()`, `_e()`, and related WordPress i18n functions with the `author-profile-blocks` text domain.

== Screenshots ==

1. Author Profile block in the Gutenberg editor — content elements panel open.
2. Style tab — avatar shape, typography controls, colors, and layout presets.
3. Author Grid block — multiple authors displayed in responsive 3-column card layout.
4. Author Carousel block — sliding author cards with the modern-cards preset active.
5. Author List block — detailed display style with two-column image and bio layout.

== Changelog ==

= 1.1.0 =
* Add: Team Member CPT (`apbl_team_member`) — title, editor, thumbnail, menu-order; position and social profiles meta; REST-enabled.
* Add: Department taxonomy (`apbl_department`) — hierarchical, attached to both team members and WordPress users; REST-enabled.
* Add: AuthorDataProvider service — normalises WP Users and Team Members into a shared author shape; result cache cleared on profile update and team-member save.
* Add: 6 new user meta fields — department, skills, location, phone, availability, website label; all REST-exposed and shown on the WP profile screen.
* Add: Four shortcodes — [apbl_profile], [apbl_grid], [apbl_list], [apbl_carousel] — all supporting source, style, and display toggle attributes.
* Add: Author Profile classic widget — wraps [apbl_profile] with author picker, style selector, and show-socials/show-bio toggles.

= 1.0.4 =
* Fix: blocks render blank on frontend when no author selected (error div was leaking to visitors).
* Fix: carousel broken on classic themes — jquery declared as dependency so Slick initialises correctly.
* Fix: production zip was missing vendor/autoload.php on fresh installs from WordPress.org.
* Fix: CSS color/length injection — all color and length attributes now pass through sanitization helpers.
* Fix: dead duplicate --author-custom-var-1/2 CSS variable block removed.
* Fix: template arbitrary file inclusion — get_template() now validates path is within plugin, active theme, or child theme.
* Fix: raw $_POST no longer passed as argument to do_action('author_profile_blocks_save_profile_fields').
* Fix: extract() in get_template() replaced with explicit foreach loop for wp.org review compliance.
* Fix: registration date displayed in wrong timezone — now uses mysql2date() for correct UTC to local conversion.
* Update: tested up to WordPress 6.9.

= 1.0.3 =
* Refactor: split AuthorBlockBase god-class into 7 focused traits under includes/Blocks/Concerns/.
* Refactor: promoted 5 inspector components to shared src/supports/js/components/inspector/.
* Refactor: extracted loadGoogleFont into shared useGoogleFont hook.
* Fix: author profile block missing save: () => null (silent default-save bug).
* Fix: carousel single-slide crash in Slick initADA.
* Fix: AuthorsListPreview data shape mismatch corrected.
* Fix: PHPCS clean for wp.org plugin review standard.

= 1.0.2 =
* Add: React + shadcn/ui admin SPA replacing legacy PHP settings page.
* Add: REST API endpoint /v1/settings for plugin configuration.
* Add: TypeScript migration for all JS files in src/blocks/ and src/admin/.
* Change: Tailwind v3 migrated to v4 with CSS variable-based theming.

= 1.0.1 =
* Fix: align all apbl- CSS class selectors with PHP template output.
* Fix: animation duration now reads --author-animation-duration CSS custom property.
* Improve: indigo editorial design system — refined shadows, cubic-bezier transitions, animated accent reveals.
* Update: tested up to WordPress 6.7.

= 1.0.0 =
* Initial release.
* Author Profile block with four content order variants and full typography/avatar controls.
* Author Grid block with 1-4 column responsive grid and five style presets.
* Author List block with compact, detailed, and minimal display styles.
* Author Carousel block with Slick slider integration and seven style presets.
* Indigo editorial design system.
* Seven entrance animation types per block.
* Five hover effect types per block.
* Social icon support: Facebook, Twitter/X, LinkedIn, Instagram, website.
* Extended WordPress user profile fields: position, bio, social links, member-since label.
* PHP template system with full theme-override support.
* CSS custom property architecture for runtime styling.
* Translation-ready with author-profile-blocks text domain.

== Upgrade Notice ==

= 1.1.0 =
Feature release. Adds Team Member CPT, Department taxonomy, four shortcodes, Author Profile widget, and six new user meta fields. Fully backward compatible — existing blocks and user data are unchanged.

= 1.0.4 =
Security and bug-fix release. Fixes CSS injection, template path traversal, blocks blank on frontend, and carousel init on classic themes. Upgrade recommended for all users.

= 1.0.3 =
Refactoring and bug-fix release. Splits the author block base class into focused traits and fixes several frontend rendering issues. Recommended upgrade.

= 1.0.2 =
Major feature update. Replaces the legacy PHP settings page with a React/shadcn admin SPA and migrates all JS to TypeScript. Recommended upgrade.

= 1.0.1 =
Bug-fix and polish release. Fixes CSS class selectors and animation timing. Refines the indigo design system. Recommended upgrade.

= 1.0.0 =
Initial release — no upgrade required.

== Support ==

* WordPress.org support forum: https://wordpress.org/support/plugin/author-profile-blocks/
* GitHub issues: https://github.com/mralaminahamed/author-profile-blocks/issues
