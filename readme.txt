=== Author Profile Blocks ===
Contributors:      mralaminahamed
Tags:              block, gutenberg, author, profile, team
Tested up to:      6.7
Stable tag:        1.0.1
Requires at least: 6.0
Requires PHP:      7.4
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Display WordPress user profiles in beautiful, customizable Gutenberg blocks — grid, carousel, list, and single profile.

== Description ==

**Author Profile Blocks** is a modern WordPress plugin that showcases user profiles using four fully-featured Gutenberg blocks. It's ideal for team pages, contributor sections, author bios, and any use case where you want to display WordPress users in a polished, branded layout.

The plugin works directly with your existing WordPress users — no custom post types, no content duplication.

=== Four Block Types ===

**Author Profile** — Single author showcase with full control over layout order, avatar shape, typography, and social links. Four content order variants: image-content, content-image, image-top, content-top.

**Author Grid** — Responsive grid of multiple authors. Supports 1–4 columns with five style presets: card, minimal, bordered, shadow, mosaic.

**Author List** — Vertical author list with three display styles: compact (image + summary), detailed (full two-column layout), and minimal (name + position only). Supports ordered or unordered lists.

**Author Carousel** — Interactive slider built on Slick. Seven style presets including modern cards, classic carousel, elegant profile, and creative gradient layout. Configurable slides, autoplay, dots, and arrows.

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
* Extended bio (plain text, shown alongside the WordPress bio)
* Social media URLs: Facebook, Twitter/X, LinkedIn, Instagram, personal website
* Custom "Member since" label

=== Performance ===

* Server-side rendering with PHP template caching
* Configurable cache duration
* Lazy loading for author avatars
* No unnecessary JavaScript on the frontend

=== Developer-Friendly ===

* Clean PHP template hierarchy — override any template from your theme
* Extensive CSS custom properties for runtime styling
* WordPress filter hooks on every rendered block
* Modern SCSS source with `@use`/`@forward` module system and `sass:color` functions
* `apbl-` namespaced CSS classes throughout

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install via **Plugins → Add New**.
2. Activate the plugin.
3. Go to **Users → Your Profile** (or any user's profile) to add extra author information.
4. Open any page or post in the Gutenberg editor and search for "author" to find the blocks.

== Frequently Asked Questions ==

= Does this create a custom post type? =

No. All blocks pull from your existing WordPress users. This avoids content duplication and keeps author data in the native user system.

= How do I add author data like position or social links? =

Edit any user profile under **Users → All Users → Edit**. The plugin adds a dedicated "Author Profile" section with position, extended bio, and social link fields.

= Can I override the block templates? =

Yes. Copy any file from `wp-content/plugins/author-profile-blocks/templates/` into `wp-content/themes/your-theme/author-profile-blocks/` and the plugin will use your version instead.

= How do I change block styles? =

Each block exposes a full Style panel in the block sidebar. For deeper overrides, all CSS uses `apbl-` prefixed classes and `--author-*` CSS custom properties, making targeted overrides straightforward.

= Can I show only authors with a specific role? =

Yes. The Grid, List, and Carousel blocks include an Author Role filter. Set it to any registered WordPress role and the block will only display users with that role.

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

= 1.0.1 =
* Fix: align all `apbl-` CSS class selectors with PHP template output — styles now apply correctly on the frontend.
* Fix: animation duration now reads `--author-animation-duration` CSS custom property (was `--animation-duration`).
* Fix: profile block entrance animations now use `has-*-animation` classes matching PHP output.
* Fix: list block layout selectors updated to match `apbl-author-compact` / `apbl-author-detailed` template output.
* Improve: SCSS fully migrated to `@use`/`@forward` module system with `sass:color` and `sass:math`.
* Improve: removed `@extend` anti-pattern; hover effects are now direct scoped rules per block.
* Improve: indigo editorial design system — refined shadows, cubic-bezier transitions, animated accent reveals.
* Improve: social icons redesigned as square-pill shape with indigo fill on hover.
* Improve: slick carousel dots styled as pill-shaped progress indicators.
* Update: tested up to WordPress 6.7.

= 1.0.0 =
* Initial release.
* Author Profile block with four content order variants and full typography/avatar controls.
* Author Grid block with 1–4 column responsive grid and five style presets.
* Author List block with compact, detailed, and minimal display styles.
* Author Carousel block with Slick slider integration and seven style presets.
* Indigo editorial design system — refined shadows, cubic-bezier transitions, animated hover reveals.
* Seven entrance animation types per block.
* Five hover effect types per block.
* Social icon support: Facebook, Twitter/X, LinkedIn, Instagram, website.
* Extended WordPress user profile fields: position, bio, social links, member-since label.
* PHP template system with full theme-override support.
* CSS custom property architecture for runtime styling.
* Modern SCSS source using `@use`/`@forward` module system with `sass:color`.
* PHP template caching with configurable duration.
* Author role filter for Grid, List, and Carousel blocks.
* Full server-side rendering — no client-side data fetching.
* Translation-ready with `author-profile-blocks` text domain.

== Upgrade Notice ==

= 1.0.1 =
Bug-fix release. CSS selectors are now correctly aligned with PHP template output — upgrading restores styling on the frontend for all blocks.

= 1.0.0 =
Initial release — no upgrade required.

== Support ==

* WordPress.org support forum: https://wordpress.org/support/plugin/author-profile-blocks/
* GitHub issues: https://github.com/mralaminahamed/author-profile-blocks/issues
