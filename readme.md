# Author Profile Blocks

WordPress plugin — four Gutenberg blocks for displaying author profiles and team members from WP Users or a Team Member CPT.

**Requires:** WordPress 6.0+, PHP 7.4+  
**Tested up to:** 6.9  
**License:** GPL-2.0-or-later

---

## Architecture

```
author-profile-blocks/
├── author-profile-blocks.php       # Entry point — constants, autoloader bootstrap
├── class-author-profile-blocks.php # Main singleton (Author_Profile_Blocks)
├── includes/
│   ├── Blocks/
│   │   ├── AuthorBlockBase.php     # Abstract base — registration, render filter, localize
│   │   ├── Concerns/               # 7 traits composing block behaviour
│   │   │   ├── BuildsBlockClasses.php
│   │   │   ├── BuildsBlockStyles.php
│   │   │   ├── HasRenderCache.php
│   │   │   ├── ProvidesMessages.php
│   │   │   ├── RendersComponents.php
│   │   │   ├── RendersLayouts.php
│   │   │   └── ResolvesAuthorData.php
│   │   ├── AuthorProfileBlock.php
│   │   ├── AuthorGridBlock.php
│   │   ├── AuthorListBlock.php
│   │   └── AuthorCarouselBlock.php
│   ├── Core/
│   │   ├── Registerable.php        # Interface: register(): void
│   │   ├── MetaDataProvider.php
│   │   └── UserMetaProvider.php    # Fluent meta field registration + REST exposure
│   ├── PostTypes/
│   │   └── TeamMemberPostType.php  # apbl_team_member CPT
│   ├── Taxonomies/
│   │   └── DepartmentTaxonomy.php  # apbl_department hierarchical taxonomy
│   ├── Services/
│   │   ├── AuthorDataProvider.php  # Normalises WP Users + Team Members → shared shape
│   │   └── AuthorProfileService.php
│   ├── Shortcodes/
│   │   ├── AuthorProfileShortcode.php   # [apbl_profile]
│   │   ├── AuthorGridShortcode.php      # [apbl_grid]
│   │   ├── AuthorListShortcode.php      # [apbl_list]
│   │   └── AuthorCarouselShortcode.php  # [apbl_carousel]
│   ├── Widgets/
│   │   └── AuthorProfileWidget.php      # Classic widget wrapping [apbl_profile]
│   ├── REST/
│   │   └── Settings.php            # /wp-json/apbl/v1/settings
│   └── Admin/
│       ├── Admin.php
│       └── PluginLinks.php
├── templates/blocks/               # PHP templates — theme-overridable
│   ├── components/                 # Shared partials (author-item, social-profiles, …)
│   ├── layouts/                    # card, compact, centered, detailed, minimal
│   ├── author-profile/             # image-content, content-image, image-top, content-top
│   ├── author-grid/
│   ├── author-list/
│   └── author-carousel/
└── src/                            # JS/TS/SCSS source (built to build/)
    ├── blocks/
    └── admin/
```

### Boot sequence

```
author-profile-blocks.php
  └─ Author_Profile_Blocks::get_instance()
       ├─ register_services()     → DepartmentTaxonomy, TeamMemberPostType,
       │                            AuthorDataProvider, AuthorProfileService,
       │                            UserMetaProvider (10 meta fields), shortcodes
       ├─ register_blocks()       → AuthorProfileBlock, AuthorGridBlock,
       │                            AuthorListBlock, AuthorCarouselBlock
       └─ init_components()       → each block init(), shortcodes init(),
                                    widgets_init → register_widget()
```

---

## Data Layer

### AuthorDataProvider

Normalises both WP Users and `apbl_team_member` posts into one shape.

```php
$provider = author_profile_blocks()->get_author_data_provider();

$author  = $provider->get_author( $id, 'user' );        // or 'team_member'
$authors = $provider->get_authors( [ 'source' => 'team_member', 'number' => 10 ] );
$provider->clear_cache();
```

Normalised shape:

| Key | Source |
|---|---|
| `id` | `WP_User->ID` / `WP_Post->ID` |
| `name` | `display_name` / post title |
| `position` | `apbl_author_position` meta / `apbl_tm_position` meta |
| `bio` | `description` meta / post content |
| `avatar_url` | `get_avatar_url()` / `get_the_post_thumbnail_url()` |
| `socials` | `apbl_social_profiles` meta / `apbl_tm_social_profiles` meta |
| `department` | `apbl_department` user meta / taxonomy term |
| `skills` | `apbl_skills` meta |
| `location` | `apbl_location` meta |
| `phone` | `apbl_phone` meta |
| `availability` | `apbl_availability` meta |
| `website_label` | `apbl_website_label` meta |
| `source` | `'user'` \| `'team_member'` |
| `post_count` | `count_user_posts()` / `0` |
| `joined` | `user_registered` / `post_date` |

Result cache is keyed `{source}_{id}` and cleared on `profile_update` and `save_post_apbl_team_member`.

---

## Custom Post Type — Team Member (`apbl_team_member`)

```php
TeamMemberPostType::POST_TYPE  // 'apbl_team_member'
```

- Supports: `title`, `editor`, `thumbnail`, `menu-order`
- `show_in_rest: true`, public
- Taxonomy: `apbl_department`
- Post meta (REST-enabled): `apbl_tm_position` (string), `apbl_tm_social_profiles` (string)

---

## Taxonomy — Department (`apbl_department`)

```php
DepartmentTaxonomy::TAXONOMY  // 'apbl_department'
```

- Hierarchical, attached to both `apbl_team_member` and `user` object types
- `show_in_rest: true`

---

## User Meta Fields

All fields registered via `UserMetaProvider::add_meta_field()`, sanitised, and exposed in REST.

| Meta key | Type | Profile label |
|---|---|---|
| `apbl_author_description` | string | Extended bio |
| `apbl_author_position` | string | Position / job title |
| `apbl_social_profiles` | string | Social media URLs |
| `apbl_member_since_label` | string | Member since label |
| `apbl_department` | string | Department |
| `apbl_skills` | string | Skills |
| `apbl_location` | string | Location |
| `apbl_phone` | string | Phone |
| `apbl_availability` | string | Availability |
| `apbl_website_label` | string | Website label |

---

## Block Registration

Each block class extends `AuthorBlockBase implements Registerable`:

```php
// Minimal concrete block
class MyBlock extends AuthorBlockBase {
    public function get_block_name(): string {
        return 'my-block'; // matches build/blocks/my-block/block.json
    }
}
```

`AuthorBlockBase::register()` calls `register_block_type( $build_path, $args )`.  
Override `get_render_callback()` to return a PHP render callback; return `null` (default) for block.json `render`.

Blocks are wired in `Author_Profile_Blocks::register_blocks()`:

```php
add_action( 'author_profile_blocks_register_blocks', function( $plugin ) {
    $plugin->register_block( new MyBlock() );
} );
```

### `get_source_attribute()` helper

`AuthorBlockBase::get_source_attribute()` returns the shared attribute definition for Phase 2 blocks that switch between WP Users and Team Member sources:

```php
// In a Phase 2 block's attribute map:
'source' => $this->get_source_attribute(),
// → [ 'type' => 'string', 'enum' => ['user', 'team_member'], 'default' => 'user' ]
```

---

## Shortcodes

Shortcodes are registered via `Author_Profile_Blocks::register_shortcodes()`, mirroring the `register_blocks()` pattern.

| Shortcode | Key attributes |
|---|---|
| `[apbl_profile]` | `id`, `source` (`user`\|`team_member`), `style`, `show_socials`, `show_bio` |
| `[apbl_grid]` | `ids`, `source`, `role`, `columns` (default 3), `style`, `number` (default 10) |
| `[apbl_list]` | `ids`, `source`, `role`, `style` (default `detailed`), `number` |
| `[apbl_carousel]` | `ids`, `source`, `role`, `style` (default `modern`), `autoplay`, `number` |

All shortcodes render via `ob_start()` / `ob_get_clean()` using the existing PHP template hierarchy.

Register a custom shortcode at runtime:

```php
add_action( 'author_profile_blocks_register_shortcodes', function( $plugin ) {
    $plugin->register_shortcode( new MyCustomShortcode() );
} );
```

---

## Classic Widget

`AuthorProfileWidget` (ID: `apbl_author_profile`) wraps `[apbl_profile]`. Controls: author picker (up to 100 users), style select, show-socials checkbox, show-bio checkbox.

---

## Template Override

Copy any file from `templates/` into your theme:

```
wp-content/themes/my-theme/author-profile-blocks/blocks/components/author-item.php
```

The plugin resolves templates via `get_template()` → `locate_template()` → `author_profile_blocks_locate_template` filter, checking theme/child-theme directories before falling back to the plugin.

---

## Hooks Reference

### Actions

| Hook | When |
|---|---|
| `author_profile_blocks_init` | After all components initialised |
| `author_profile_blocks_activated` | On plugin activation |
| `author_profile_blocks_deactivated` | On plugin deactivation |
| `author_profile_blocks_register_blocks` | Before blocks are registered — add custom blocks here |
| `author_profile_blocks_blocks_registered` | After all blocks registered |
| `author_profile_blocks_block_registered` | After each individual block registered (`$block_name, $instance`) |
| `author_profile_blocks_register_shortcodes` | Before shortcodes init — add custom shortcodes here |
| `author_profile_blocks_shortcodes_registered` | After all shortcodes registered |
| `author_profile_blocks_register_rest_fields` | Before REST field registration |
| `author_profile_blocks_profile_fields` | Inside the user profile "Author Profile" section |
| `author_profile_blocks_save_profile_fields` | After profile fields saved (`$user_id`) |
| `author_profile_blocks_before_template_part` | Before a template part is included |
| `author_profile_blocks_after_template_part` | After a template part is included |

### Filters

| Filter | What it controls |
|---|---|
| `author_profile_blocks_block_args` | Args array passed to `register_block_type()` |
| `author_profile_blocks_rendered_block` | Final rendered HTML for all blocks |
| `author_profile_blocks_rendered_{block_name}` | Final rendered HTML for a specific block |
| `author_profile_blocks_author_data` | Normalised author array from `AuthorProfileService` |
| `author_profile_blocks_author_query_args` | `WP_User_Query` / `WP_Query` args before execution |
| `author_profile_blocks_authors` | Author array after query |
| `author_profile_blocks_localized_block_data` | Data passed to `wp_localize_script()` |
| `author_profile_blocks_get_template` | Template path before include |
| `author_profile_blocks_get_template_part` | Template part path before include |
| `author_profile_blocks_locate_template` | Resolved template path (theme override entry point) |
| `author_profile_blocks_setting` | Single plugin setting value |
| `author_profile_blocks_settings` | All plugin settings array |

---

## Constants

```php
APBL_VERSION      // '1.1.0'
APBL_PLUGIN_FILE  // absolute path to author-profile-blocks.php
APBL_PLUGIN_PATH  // plugin directory with trailing slash
APBL_PLUGIN_URL   // plugin URL with trailing slash
```

---

## REST API

`/wp-json/apbl/v1/settings` — read/write plugin settings. Requires `manage_options` capability.

---

## Development

**Prerequisites:** Node 18+, PHP 7.4+, Composer 2

```bash
composer install
yarn install

# Watch (JS/CSS)
yarn start

# Production build
yarn build

# Type check
yarn type

# Lint JS + CSS + docs
yarn lint
```

### PHP

```bash
# Run tests
composer test

# Filter to a test
composer test-f -- AuthorDataProviderTest

# Coverage report (HTML → tests/coverage/html/)
composer test:coverage

# PHPCS (WordPress Coding Standards)
composer phpcs

# PHPCBF auto-fix
composer phpcbf

# PHPStan static analysis
composer phpstan
```

### Release

```bash
composer release
# Builds zip to release/author-profile-blocks.zip
```

---

## Namespace & Autoloading

PSR-4 via Composer:

```
AuthorProfileBlocks\  →  includes/
AuthorProfileBlocks\Test\  →  tests/php/src/
```

Main plugin class (`Author_Profile_Blocks`) lives in `class-author-profile-blocks.php` and is loaded explicitly — it predates the PSR-4 map.

---

## Testing

PHPUnit 9.6. Test suites under `tests/php/src/`:

```
Unit/
  Blocks/       AuthorBlockBaseTest
  Core/         UserMetaProviderTest
  Plugin/       PluginTest (shortcode/widget wiring)
  PostTypes/    TeamMemberPostTypeTest
  Services/     AuthorDataProviderTest
  Shortcodes/   AuthorProfileShortcodeTest
  Taxonomies/   DepartmentTaxonomyTest
  Widgets/      AuthorProfileWidgetTest
Integration/
  PluginTest    (constants, singleton, block wiring)
```

357 tests / 970 assertions as of v1.1.0.

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md).
