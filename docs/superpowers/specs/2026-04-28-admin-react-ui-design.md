# Admin React UI — Design Spec

**Date:** 2026-04-28
**Scope:** Replace PHP template-based admin settings UI with React + shadcn/ui
**Approach:** Full SPA replacement — PHP outputs mount point only, React owns all UI

---

## 1. Architecture Overview

```
PHP side                              React side
────────────────────────────          ────────────────────────────────────
Admin.php                             src/admin/
  enqueue_scripts()        ──JS──▶     index.tsx          mount → <App />
  settings_page()          ──PHP──▶    App.tsx            createHashRouter
  REST/Settings.php                    components/Pages/
    GET  /v1/settings      ◀─────────    RootLayout.tsx   sticky nav
    POST /v1/settings      ◀─────────    SettingsPage.tsx tabbed settings
                                         PluginsPage.tsx  WP.org plugin grid
                                         AboutPage.tsx    plugin info + links
                                       hooks/
                                         useSettings.ts   fetch + save
                                       types/index.ts     interfaces
                                       components/ui/     shadcn components
```

- `settings_page()` outputs only `<div id="apbl-admin-root"></div>`
- PHP `register_settings()`, all `add_settings_field()`, all field callbacks — removed
- `templates/admin/settings-page.php` + `templates/admin/fields/*.php` — deleted
- `sanitize_settings()` logic moves into REST handler

---

## 2. REST API

**File:** `includes/REST/Settings.php`

Registered via `rest_api_init` hook.

### GET `/wp-json/author-profile-blocks/v1/settings`

Permission: `current_user_can('manage_options')`

Response:
```json
{
  "author_roles": ["administrator", "editor", "author"],
  "avatar_size": 150,
  "social_platforms": ["facebook", "twitter", "linkedin", "instagram"],
  "show_email": false,
  "cache_duration": 24
}
```

### POST `/wp-json/author-profile-blocks/v1/settings`

Permission: `current_user_can('manage_options')`

Body: same shape as GET response.

Sanitization:
- `author_roles` — `array_map('sanitize_text_field', ...)`, validate against `wp_roles()->roles`
- `avatar_size` — `absint()`, clamp 32–512
- `social_platforms` — `array_map('sanitize_text_field', ...)`, validate against allowed list
- `show_email` — `rest_sanitize_boolean()`
- `cache_duration` — `absint()`, clamp 1–168

Response: updated settings object (same shape as GET).

Auth: `wp_rest` nonce sent as `X-WP-Nonce` header via `@wordpress/api-fetch` middleware.

---

## 3. PHP Changes

### `Admin.php`

**`enqueue_scripts()`** — fire on `settings_page_author-profile-blocks` hook only:
```php
wp_enqueue_script(
    'apbl-admin',
    plugin_dir_url(APBL_PLUGIN_FILE) . 'build/admin/index.js',
    ['wp-element', 'wp-i18n', 'wp-api-fetch'],
    APBL_VERSION,
    true
);
wp_enqueue_style(
    'apbl-admin-style',
    plugin_dir_url(APBL_PLUGIN_FILE) . 'build/admin/style-index.css',
    [],
    APBL_VERSION
);
wp_localize_script('apbl-admin', 'apblAdmin', [
    'restUrl'   => rest_url('author-profile-blocks/v1/'),
    'restNonce' => wp_create_nonce('wp_rest'),
    'version'   => APBL_VERSION,
    'wpRoles'   => array_map(fn($r) => $r['name'], wp_roles()->roles),
]);
```

**`settings_page()`**:
```php
public function settings_page(): void {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'author-profile-blocks'));
    }
    echo '<div id="apbl-admin-root"></div>';
}
```

**Removed from `Admin.php`:**
- `register_settings()` and all `add_settings_section()` / `add_settings_field()` calls
- All field callback methods (`author_roles_field_callback`, etc.)
- `sanitize_settings()` — logic moves to `REST/Settings.php`
- `general_settings_section_callback()`, `display_settings_section_callback()`, `performance_settings_section_callback()`
- `render_settings_fallback()` — dead code, already unused

**`get_default_settings()` and `get_settings()`** — keep, still used by blocks.

### New file: `includes/REST/Settings.php`

Namespace `AuthorProfileBlocks\REST`. Registered via `rest_api_init` in main plugin class.

### Deleted files

- `templates/admin/settings-page.php`
- `templates/admin/fields/author-roles.php`
- `templates/admin/fields/avatar-size.php`
- `templates/admin/fields/social-platforms.php`
- `templates/admin/fields/show-email.php`
- `templates/admin/fields/cache-duration.php`

---

## 4. React App Structure

```
src/admin/
├── index.tsx                    # createRoot → <App />
├── style.css                    # Tailwind v4 entry (exists)
├── App.tsx                      # createHashRouter, routes
├── types/
│   └── index.ts                 # Settings, WPPlugin, apblAdmin window type
├── hooks/
│   └── useSettings.ts           # fetch GET on mount, POST on save, loading/error/saved state
└── components/
    └── Pages/
        ├── RootLayout.tsx       # sticky nav header
        ├── SettingsPage.tsx     # <Tabs> with 3 panels, save button
        ├── PluginsPage.tsx      # api.wordpress.org grid, filter out this plugin
        └── AboutPage.tsx        # plugin info, version, GitHub/docs/support links
    └── ui/                      # shadcn: button, card, input, label, switch, badge, tabs, select, separator
```

### Routing (`App.tsx`)

```
/           → SettingsPage  (default)
/plugins    → PluginsPage
/about      → AboutPage
```

Uses `createHashRouter` — hash routing works inside WordPress admin without server config.

### `RootLayout.tsx`

Sticky top bar matching easycommerce-fakerpress pattern:
```
[Author Profile Blocks]  Settings  |  Our Plugins  |  About
```

### `SettingsPage.tsx`

Three tabs:
- **General** — Author Roles: multi-checkbox list using `wpRoles` from `window.apblAdmin`
- **Display** — Avatar Size (`<Input type="number">`), Social Platforms (6 `<Switch>` rows), Show Email (`<Switch>` with warning badge)
- **Performance** — Cache Duration (`<Input type="number">`)

Save button at bottom of each tab. Shows loading spinner while saving, success state for 2s.

### `PluginsPage.tsx`

Fetches `https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[author]=mralaminahamed&request[per_page]=20`

Filters out slug `author-profile-blocks`. Grid 1→2→3 cols. Skeleton loaders while fetching. `PluginCard` component: icon, name, version, description, star rating, active installs, "View on WordPress.org" link.

### `AboutPage.tsx`

- Plugin info card: name (`Author Profile Blocks`), version from `window.apblAdmin.version`, short description
- Links: GitHub (`https://github.com/mralaminahamed/author-profile-blocks`), Documentation (same + `#readme`), Support (`/issues`)
- Author card: Al Amin Ahamed

### `useSettings.ts`

```ts
// State: settings, loading, saving, error, saved
// On mount: GET /v1/settings via apiFetch → set settings
// save(newSettings): POST /v1/settings → update state, set saved=true for 2s
```

Uses `@wordpress/api-fetch` (handles nonce automatically).

### `types/index.ts`

```ts
interface Settings {
  author_roles: string[];
  avatar_size: number;
  social_platforms: string[];
  show_email: boolean;
  cache_duration: number;
}

interface WPPlugin {
  name: string; slug: string; version: string;
  short_description: string;
  icons?: { '1x'?: string; '2x'?: string; svg?: string };
  rating: number; num_ratings: number; active_installs: number;
}

declare global {
  interface Window {
    apblAdmin: {
      restUrl: string;
      restNonce: string;
      version: string;
      wpRoles: Record<string, string>;
    };
  }
}
```

---

## 5. shadcn Components to Install

```bash
npx shadcn add button card input label switch badge tabs select separator
```

All land in `src/components/ui/`.

---

## 6. Constraints

- `Admin::get_default_settings()` and `Admin::get_settings()` kept — used by blocks for defaults
- `tailwindcss-animate` already installed — powers shadcn transitions
- `react-router-dom` already in dependencies
- `@wordpress/api-fetch` already in devDependencies — used for REST calls
- `@wordpress/element`, `@wordpress/i18n`, `@wordpress/html-entities` already in devDependencies
- Block SCSS files untouched
- Social platforms allowed list: `['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'website']`
