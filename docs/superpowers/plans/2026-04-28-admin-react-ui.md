# Admin React UI Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the PHP template-based admin settings UI with a React + shadcn/ui SPA featuring Settings (tabbed), Our Plugins, and About pages.

**Architecture:** PHP outputs only `<div id="apbl-admin-root">`. A new `includes/REST/Settings.php` handles GET/POST for the 5 plugin settings. React mounts via `createHashRouter` with three routes: `/` (Settings), `/plugins` (WP.org grid), `/about` (plugin info). `@wordpress/api-fetch` with nonce middleware handles all REST calls.

**Tech Stack:** React 18, TypeScript, react-router-dom v7, @wordpress/api-fetch, @wordpress/element, @wordpress/i18n, shadcn/ui (button/card/input/label/switch/badge/tabs/select/separator), Tailwind CSS v4, lucide-react

---

## File Map

| Action | Path | Purpose |
|---|---|---|
| Run | `npx shadcn@latest add ...` | Install shadcn components to `src/components/ui/` |
| Create | `includes/REST/Settings.php` | GET + POST /v1/settings REST endpoints |
| Modify | `includes/Admin/Admin.php` | New enqueue, simplified settings_page, remove WP Settings API |
| Modify | `class-author-profile-blocks.php` | Add REST\Settings use + instantiation |
| Delete | `templates/admin/settings-page.php` + `fields/*.php` | Removed — React owns UI |
| Modify | `src/admin/style.css` | Update @source paths for new component structure |
| Create | `src/admin/types/index.ts` | Settings, WPPlugin interfaces + window.apblAdmin type |
| Create | `src/admin/hooks/useSettings.ts` | Fetch/save settings via apiFetch |
| Modify | `src/admin/index.tsx` | Wire apiFetch nonce middleware, render `<App />` |
| Create | `src/admin/App.tsx` | createHashRouter with 3 routes + RootLayout |
| Create | `src/admin/components/Pages/RootLayout.tsx` | Sticky nav header |
| Create | `src/admin/components/Pages/SettingsPage.tsx` | Tabbed settings UI |
| Create | `src/admin/components/Pages/PluginsPage.tsx` | WP.org plugin grid |
| Create | `src/admin/components/Pages/AboutPage.tsx` | Plugin info + links |

---

### Task 1: Install shadcn/ui components

**Files:**
- Create: `src/components/ui/button.tsx`, `card.tsx`, `input.tsx`, `label.tsx`, `switch.tsx`, `badge.tsx`, `tabs.tsx`, `select.tsx`, `separator.tsx`

- [ ] **Step 1: Run shadcn add**

Working directory: `/Users/alamin/Sites/wp-plugin-dev/wp-content/plugins/author-profile-blocks`

```bash
npx shadcn@latest add button card input label switch badge tabs select separator --yes
```

Expected: files created in `src/components/ui/`. If prompted about overwriting, accept. If the CLI asks about `tailwind.config`, it's fine — `components.json` has `"config": ""` which is correct for Tailwind v4.

- [ ] **Step 2: Verify components exist**

```bash
ls src/components/ui/
```

Expected output includes: `button.tsx  card.tsx  input.tsx  label.tsx  switch.tsx  badge.tsx  tabs.tsx  select.tsx  separator.tsx`

- [ ] **Step 3: Verify build still passes**

```bash
yarn build 2>&1 | tail -3
```

Expected: `webpack X.X.X compiled successfully`

- [ ] **Step 4: Commit**

```bash
git add src/components/ui/
git commit -m "feat(admin): install shadcn/ui components (button, card, input, label, switch, badge, tabs, select, separator)"
```

---

### Task 2: Create PHP REST Settings endpoint

**Files:**
- Create: `includes/REST/Settings.php`

- [ ] **Step 1: Create the REST Settings class**

Create `includes/REST/Settings.php`:

```php
<?php
declare(strict_types=1);
/**
 * REST Settings Controller
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\REST;

use AuthorProfileBlocks\Admin\Admin;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles GET and POST for plugin settings via REST API.
 */
class Settings {

	const NAMESPACE = 'author-profile-blocks/v1';
	const ROUTE     = '/settings';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			self::ROUTE,
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	public function check_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	public function get_settings(): WP_REST_Response {
		$settings               = Admin::get_settings();
		$settings['show_email'] = (bool) $settings['show_email'];
		return new WP_REST_Response( $settings, 200 );
	}

	public function update_settings( WP_REST_Request $request ): WP_REST_Response {
		$params    = $request->get_json_params() ?? array();
		$sanitized = array();

		$valid_roles = array_keys( wp_roles()->roles );
		if ( isset( $params['author_roles'] ) && is_array( $params['author_roles'] ) ) {
			$sanitized['author_roles'] = array_values(
				array_filter(
					array_map( 'sanitize_text_field', $params['author_roles'] ),
					fn( $r ) => in_array( $r, $valid_roles, true )
				)
			);
		}

		if ( isset( $params['avatar_size'] ) ) {
			$sanitized['avatar_size'] = wp_clamp( absint( $params['avatar_size'] ), 32, 512 );
		}

		$valid_platforms = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'website' );
		if ( isset( $params['social_platforms'] ) && is_array( $params['social_platforms'] ) ) {
			$sanitized['social_platforms'] = array_values(
				array_filter(
					array_map( 'sanitize_text_field', $params['social_platforms'] ),
					fn( $p ) => in_array( $p, $valid_platforms, true )
				)
			);
		}

		if ( isset( $params['show_email'] ) ) {
			$sanitized['show_email'] = rest_sanitize_boolean( $params['show_email'] ) ? 1 : 0;
		}

		if ( isset( $params['cache_duration'] ) ) {
			$sanitized['cache_duration'] = wp_clamp( absint( $params['cache_duration'] ), 1, 168 );
		}

		$updated               = array_merge( Admin::get_settings(), $sanitized );
		$updated['show_email'] = (bool) $updated['show_email'];
		update_option( 'author_profile_blocks_settings', array_merge( Admin::get_settings(), $sanitized ) );

		return new WP_REST_Response( $updated, 200 );
	}
}
```

- [ ] **Step 2: Commit**

```bash
git add includes/REST/Settings.php
git commit -m "feat(rest): add Settings REST endpoint GET+POST /v1/settings"
```

---

### Task 3: Update Admin.php

**Files:**
- Modify: `includes/Admin/Admin.php`

Replace the entire file content. The new version keeps only `__construct`, `init`, `enqueue_scripts`, `add_menu_pages`, `settings_page`, `get_default_settings`, `get_settings`. All WP Settings API methods are removed.

- [ ] **Step 1: Replace includes/Admin/Admin.php**

```php
<?php
declare(strict_types=1);
/**
 * Admin Class
 *
 * @package AuthorProfileBlocks
 * @license GPL-2.0-or-later
 */

namespace AuthorProfileBlocks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class for managing plugin settings and admin interface.
 */
class Admin {

	public function __construct() {
		$this->init();
	}

	private function init(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
	}

	public function enqueue_scripts( string $hook ): void {
		if ( 'settings_page_author-profile-blocks' !== $hook ) {
			return;
		}

		$asset_file = plugin_dir_path( APBL_PLUGIN_FILE ) . 'build/admin/index.asset.php';
		$asset      = file_exists( $asset_file )
			? require $asset_file
			: array( 'dependencies' => array(), 'version' => APBL_VERSION );

		wp_enqueue_script(
			'apbl-admin',
			plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/admin/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'apbl-admin-style',
			plugin_dir_url( APBL_PLUGIN_FILE ) . 'build/admin/style-index.css',
			array(),
			$asset['version']
		);

		wp_localize_script(
			'apbl-admin',
			'apblAdmin',
			array(
				'restUrl'   => rest_url( 'author-profile-blocks/v1/' ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
				'version'   => APBL_VERSION,
				'wpRoles'   => array_map( fn( $r ) => $r['name'], wp_roles()->roles ),
			)
		);
	}

	public function add_menu_pages(): void {
		add_options_page(
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			__( 'Author Profile Blocks', 'author-profile-blocks' ),
			'manage_options',
			'author-profile-blocks',
			array( $this, 'settings_page' )
		);
	}

	public function settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'author-profile-blocks' ) );
		}
		echo '<div id="apbl-admin-root"></div>';
	}

	public static function get_default_settings(): array {
		return array(
			'author_roles'     => array( 'administrator', 'editor', 'author' ),
			'avatar_size'      => 150,
			'social_platforms' => array( 'facebook', 'twitter', 'linkedin', 'instagram' ),
			'show_email'       => 0,
			'cache_duration'   => 24,
		);
	}

	public static function get_settings(): array {
		return wp_parse_args(
			get_option( 'author_profile_blocks_settings', array() ),
			self::get_default_settings()
		);
	}
}
```

- [ ] **Step 2: Commit**

```bash
git add includes/Admin/Admin.php
git commit -m "feat(admin): replace WP Settings API with REST-backed React mount point"
```

---

### Task 4: Register REST Settings in plugin bootstrap

**Files:**
- Modify: `class-author-profile-blocks.php`

- [ ] **Step 1: Add use statement**

In `class-author-profile-blocks.php`, after line 13 (the existing `use` block), add:

```php
use AuthorProfileBlocks\REST\Settings as REST_Settings;
```

The use block becomes:
```php
use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Admin\PluginLinks;
use AuthorProfileBlocks\Blocks\Author_Block_Base;
use AuthorProfileBlocks\Blocks\Author_Carousel_Block;
use AuthorProfileBlocks\Blocks\Author_Grid_Block;
use AuthorProfileBlocks\Blocks\Author_List_Block;
use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use AuthorProfileBlocks\Core\User_Meta_Provider;
use AuthorProfileBlocks\REST\Settings as REST_Settings;
use AuthorProfileBlocks\Services\Author_Profile_Service;
```

- [ ] **Step 2: Instantiate REST_Settings in init_components() outside is_admin()**

In `class-author-profile-blocks.php`, `init_components()` at line ~250. The `is_admin()` block ends at line ~262. Add `new REST_Settings()` immediately after it:

Change this section (lines ~258–266):
```php
		// Initialize admin components.
		if ( is_admin() ) {
			new PluginLinks();
			new Admin();
		}

		// Register hooks in groups for better organization.
```

To:
```php
		// Initialize admin components.
		if ( is_admin() ) {
			new PluginLinks();
			new Admin();
		}

		// Register REST API routes (available on all requests, not just admin).
		new REST_Settings();

		// Register hooks in groups for better organization.
```

`REST_Settings` must be outside `is_admin()` because REST API requests (`/wp-json/...`) do not run in admin context.

- [ ] **Step 3: Commit**

```bash
git add class-author-profile-blocks.php
git commit -m "feat(rest): register REST Settings controller in plugin bootstrap"
```

---

### Task 5: Delete PHP templates

**Files:**
- Delete: `templates/admin/settings-page.php`
- Delete: `templates/admin/fields/author-roles.php`
- Delete: `templates/admin/fields/avatar-size.php`
- Delete: `templates/admin/fields/social-platforms.php`
- Delete: `templates/admin/fields/show-email.php`
- Delete: `templates/admin/fields/cache-duration.php`

- [ ] **Step 1: Delete template files**

```bash
git rm templates/admin/settings-page.php \
       templates/admin/fields/author-roles.php \
       templates/admin/fields/avatar-size.php \
       templates/admin/fields/social-platforms.php \
       templates/admin/fields/show-email.php \
       templates/admin/fields/cache-duration.php
```

- [ ] **Step 2: Remove empty fields directory if now empty**

```bash
rmdir templates/admin/fields 2>/dev/null || true
```

- [ ] **Step 3: Commit**

```bash
git commit -m "chore: delete PHP admin template files replaced by React UI"
```

---

### Task 6: Create TypeScript types

**Files:**
- Create: `src/admin/types/index.ts`

- [ ] **Step 1: Create src/admin/types/index.ts**

```ts
export interface Settings {
	author_roles: string[];
	avatar_size: number;
	social_platforms: string[];
	show_email: boolean;
	cache_duration: number;
}

export interface WPPlugin {
	name: string;
	slug: string;
	version: string;
	short_description: string;
	icons?: { '1x'?: string; '2x'?: string; svg?: string };
	rating: number;
	num_ratings: number;
	active_installs: number;
}

declare global {
	interface Window {
		apblAdmin: {
			restUrl: string;
			restNonce: string;
			version: string;
			wpRoles: Record< string, string >;
		};
	}
}
```

- [ ] **Step 2: Verify TypeScript compiles**

```bash
npx tsc --noEmit 2>&1 | grep "types/index" | head -5
```

Expected: no output (no errors for this file).

- [ ] **Step 3: Commit**

```bash
git add src/admin/types/index.ts
git commit -m "feat(admin): add TypeScript types for Settings, WPPlugin, window.apblAdmin"
```

---

### Task 7: Create useSettings hook + update style.css @source

**Files:**
- Create: `src/admin/hooks/useSettings.ts`
- Modify: `src/admin/style.css`

- [ ] **Step 1: Create src/admin/hooks/useSettings.ts**

```ts
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import type { Settings } from '../types';

const DEFAULT_SETTINGS: Settings = {
	author_roles: [ 'administrator', 'editor', 'author' ],
	avatar_size: 150,
	social_platforms: [ 'facebook', 'twitter', 'linkedin', 'instagram' ],
	show_email: false,
	cache_duration: 24,
};

export function useSettings() {
	const [ settings, setSettings ] = useState< Settings >( DEFAULT_SETTINGS );
	const [ loading, setLoading ] = useState( true );
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState< string | null >( null );
	const [ saved, setSaved ] = useState( false );

	useEffect( () => {
		apiFetch< Settings >( { path: '/author-profile-blocks/v1/settings' } )
			.then( ( data ) => {
				setSettings( data );
				setLoading( false );
			} )
			.catch( () => {
				setError( 'Could not load settings.' );
				setLoading( false );
			} );
	}, [] );

	const save = async ( newSettings: Settings ): Promise< void > => {
		setSaving( true );
		setError( null );
		try {
			const updated = await apiFetch< Settings >( {
				path: '/author-profile-blocks/v1/settings',
				method: 'POST',
				data: newSettings,
			} );
			setSettings( updated );
			setSaved( true );
			setTimeout( () => setSaved( false ), 2000 );
		} catch ( err ) {
			setError( err instanceof Error ? err.message : 'Save failed.' );
		} finally {
			setSaving( false );
		}
	};

	return { settings, setSettings, loading, saving, error, saved, save };
}
```

- [ ] **Step 2: Update @source paths in src/admin/style.css**

Replace:
```css
@source "./pages/**/*.{js,ts,jsx,tsx}";
```

With:
```css
@source "./App.tsx";
@source "./components/**/*.{js,ts,jsx,tsx}";
@source "./hooks/**/*.{js,ts,jsx,tsx}";
```

The full `@source` block after edit (keep everything else unchanged):
```css
@source "../components/**/*.{js,ts,jsx,tsx}";
@source "./App.tsx";
@source "./components/**/*.{js,ts,jsx,tsx}";
@source "./hooks/**/*.{js,ts,jsx,tsx}";
@source "./index.tsx";
```

- [ ] **Step 3: Commit**

```bash
git add src/admin/hooks/useSettings.ts src/admin/style.css
git commit -m "feat(admin): add useSettings hook + update Tailwind @source paths"
```

---

### Task 8: Create App.tsx + update index.tsx

**Files:**
- Create: `src/admin/App.tsx`
- Modify: `src/admin/index.tsx`

- [ ] **Step 1: Create src/admin/App.tsx**

```tsx
import { createHashRouter, RouterProvider } from 'react-router-dom';
import RootLayout from './components/Pages/RootLayout';
import SettingsPage from './components/Pages/SettingsPage';
import PluginsPage from './components/Pages/PluginsPage';
import AboutPage from './components/Pages/AboutPage';

const router = createHashRouter( [
	{
		path: '/',
		element: <RootLayout />,
		children: [
			{ index: true, element: <SettingsPage /> },
			{ path: 'plugins', element: <PluginsPage /> },
			{ path: 'about', element: <AboutPage /> },
		],
	},
] );

export default function App() {
	return <RouterProvider router={ router } />;
}
```

- [ ] **Step 2: Replace src/admin/index.tsx**

```tsx
import { createRoot } from 'react-dom/client';
import apiFetch from '@wordpress/api-fetch';
import App from './App';
import './style.css';

apiFetch.use( apiFetch.createNonceMiddleware( window.apblAdmin.restNonce ) );

const root = document.getElementById( 'apbl-admin-root' );
if ( root ) {
	createRoot( root ).render( <App /> );
}
```

- [ ] **Step 3: Verify build**

```bash
yarn build 2>&1 | tail -4
```

Expected: `webpack X.X.X compiled successfully` (will fail if Page components don't exist yet — that's OK, they'll be created in later tasks. If build fails, check the error and ensure imports can be resolved by creating empty placeholder files temporarily if needed.)

- [ ] **Step 4: Commit**

```bash
git add src/admin/App.tsx src/admin/index.tsx
git commit -m "feat(admin): add React router App + wire apiFetch nonce middleware"
```

---

### Task 9: Create RootLayout (nav shell)

**Files:**
- Create: `src/admin/components/Pages/RootLayout.tsx`

- [ ] **Step 1: Create directory and file**

```bash
mkdir -p src/admin/components/Pages
```

Create `src/admin/components/Pages/RootLayout.tsx`:

```tsx
import { Link, Outlet, useLocation } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import { Settings, Puzzle, Info } from 'lucide-react';
import { cn } from '@/lib/utils';

const NAV_LINKS = [
	{ to: '/', label: 'Settings', icon: Settings },
	{ to: '/plugins', label: 'Our Plugins', icon: Puzzle },
	{ to: '/about', label: 'About', icon: Info },
] as const;

export default function RootLayout() {
	const { pathname } = useLocation();

	return (
		<div className="min-h-screen bg-gray-50">
			<header className="sticky top-32 z-20 h-12 bg-white border-b border-gray-200 flex items-center px-6 gap-6">
				<span className="text-sm font-bold text-gray-900 mr-2">
					{ __( 'Author Profile Blocks', 'author-profile-blocks' ) }
				</span>
				{ NAV_LINKS.map( ( { to, label, icon: Icon } ) => {
					const active = to === '/' ? pathname === '/' : pathname.startsWith( to );
					return (
						<Link
							key={ to }
							to={ to }
							className={ cn(
								'flex items-center gap-1.5 text-sm transition-colors',
								active
									? 'text-blue-600 font-medium'
									: 'text-gray-500 hover:text-gray-900'
							) }
						>
							<Icon className="w-4 h-4" />
							{ __( label, 'author-profile-blocks' ) }
						</Link>
					);
				} ) }
			</header>
			<Outlet />
		</div>
	);
}
```

Note: `top-32` accounts for the WordPress admin bar height (~32px). Adjust to `top-0` if the WP admin bar is not present.

- [ ] **Step 2: Commit**

```bash
git add src/admin/components/Pages/RootLayout.tsx
git commit -m "feat(admin): add RootLayout with sticky nav (Settings | Our Plugins | About)"
```

---

### Task 10: Create SettingsPage

**Files:**
- Create: `src/admin/components/Pages/SettingsPage.tsx`

- [ ] **Step 1: Create src/admin/components/Pages/SettingsPage.tsx**

```tsx
import type { ChangeEvent } from 'react';
import { __ } from '@wordpress/i18n';
import { Save, Loader2 } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { useSettings } from '../../hooks/useSettings';
import type { Settings } from '../../types';

const SOCIAL_PLATFORMS = [
	{ key: 'facebook', label: 'Facebook' },
	{ key: 'twitter', label: 'Twitter/X' },
	{ key: 'linkedin', label: 'LinkedIn' },
	{ key: 'instagram', label: 'Instagram' },
	{ key: 'youtube', label: 'YouTube' },
	{ key: 'website', label: 'Website' },
] as const;

interface SaveBarProps {
	saving: boolean;
	saved: boolean;
	error: string | null;
	onSave: () => void;
}

function SaveBar( { saving, saved, error, onSave }: SaveBarProps ) {
	return (
		<div className="mt-6 pt-4 border-t border-gray-100 flex items-center gap-3">
			<Button onClick={ onSave } disabled={ saving } className="gap-2">
				{ saving
					? <Loader2 className="w-4 h-4 animate-spin" />
					: <Save className="w-4 h-4" /> }
				{ saving
					? __( 'Saving…', 'author-profile-blocks' )
					: saved
					? __( 'Saved!', 'author-profile-blocks' )
					: __( 'Save Settings', 'author-profile-blocks' ) }
			</Button>
			{ error && <p className="text-sm text-red-600">{ error }</p> }
		</div>
	);
}

export default function SettingsPage() {
	const { settings, setSettings, loading, saving, error, saved, save } = useSettings();
	const wpRoles = window.apblAdmin.wpRoles;

	if ( loading ) {
		return (
			<div className="p-6 flex items-center gap-2 text-gray-500">
				<Loader2 className="w-4 h-4 animate-spin" />
				{ __( 'Loading settings…', 'author-profile-blocks' ) }
			</div>
		);
	}

	const handleSave = () => save( settings );

	return (
		<div className="p-6 max-w-2xl">
			<div className="mb-6">
				<h1 className="text-2xl font-bold text-gray-900">
					{ __( 'Settings', 'author-profile-blocks' ) }
				</h1>
				<p className="text-sm text-gray-500 mt-1">
					{ __( 'Configure the Author Profile Blocks plugin.', 'author-profile-blocks' ) }
				</p>
			</div>

			<Tabs defaultValue="general">
				<TabsList className="mb-6">
					<TabsTrigger value="general">
						{ __( 'General', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="display">
						{ __( 'Display', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="performance">
						{ __( 'Performance', 'author-profile-blocks' ) }
					</TabsTrigger>
				</TabsList>

				{ /* General: Author Roles */ }
				<TabsContent value="general">
					<div className="bg-white rounded-xl border border-gray-200 p-5">
						<h2 className="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
							{ __( 'Author Roles', 'author-profile-blocks' ) }
						</h2>
						<p className="text-sm text-gray-500 mb-4">
							{ __( 'Select which user roles should be available as authors.', 'author-profile-blocks' ) }
						</p>
						<div className="space-y-3">
							{ Object.entries( wpRoles ).map( ( [ key, name ] ) => (
								<div key={ key } className="flex items-center gap-3">
									<input
										id={ `role-${ key }` }
										type="checkbox"
										checked={ settings.author_roles.includes( key ) }
										onChange={ ( e: ChangeEvent< HTMLInputElement > ) => {
											const roles = e.target.checked
												? [ ...settings.author_roles, key ]
												: settings.author_roles.filter( ( r ) => r !== key );
											setSettings( ( s: Settings ) => ( { ...s, author_roles: roles } ) );
										} }
										className="h-4 w-4 rounded border-gray-300 text-blue-600 cursor-pointer"
									/>
									<Label
										htmlFor={ `role-${ key }` }
										className="text-sm text-gray-700 cursor-pointer"
									>
										{ name as string }
									</Label>
								</div>
							) ) }
						</div>
						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>

				{ /* Display: Avatar, Social, Email */ }
				<TabsContent value="display">
					<div className="bg-white rounded-xl border border-gray-200 p-5 space-y-6">
						<h2 className="text-xs font-semibold uppercase tracking-wide text-gray-400">
							{ __( 'Display Settings', 'author-profile-blocks' ) }
						</h2>

						<div className="space-y-1.5">
							<Label className="text-sm font-medium text-gray-700">
								{ __( 'Avatar Size (px)', 'author-profile-blocks' ) }
							</Label>
							<p className="text-xs text-gray-400">
								{ __( 'Default avatar size in pixels (32–512).', 'author-profile-blocks' ) }
							</p>
							<Input
								type="number"
								min={ 32 }
								max={ 512 }
								value={ settings.avatar_size }
								className="w-32"
								onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
									setSettings( ( s: Settings ) => ( {
										...s,
										avatar_size: parseInt( e.target.value, 10 ) || 150,
									} ) )
								}
							/>
						</div>

						<div className="space-y-3">
							<Label className="text-sm font-medium text-gray-700">
								{ __( 'Social Platforms', 'author-profile-blocks' ) }
							</Label>
							<p className="text-xs text-gray-400">
								{ __( 'Enable social platforms to display in author profiles.', 'author-profile-blocks' ) }
							</p>
							{ SOCIAL_PLATFORMS.map( ( { key, label } ) => (
								<div key={ key } className="flex items-center justify-between">
									<Label
										htmlFor={ `platform-${ key }` }
										className="text-sm text-gray-700 cursor-pointer"
									>
										{ label }
									</Label>
									<Switch
										id={ `platform-${ key }` }
										checked={ settings.social_platforms.includes( key ) }
										onCheckedChange={ ( checked ) => {
											const platforms = checked
												? [ ...settings.social_platforms, key ]
												: settings.social_platforms.filter( ( p ) => p !== key );
											setSettings( ( s: Settings ) => ( { ...s, social_platforms: platforms } ) );
										} }
									/>
								</div>
							) ) }
						</div>

						<div className="flex items-start gap-3 pt-2 border-t border-gray-100">
							<Switch
								id="show-email"
								checked={ settings.show_email }
								onCheckedChange={ ( checked ) =>
									setSettings( ( s: Settings ) => ( { ...s, show_email: checked } ) )
								}
								className="mt-0.5"
							/>
							<div>
								<Label
									htmlFor="show-email"
									className="text-sm font-medium text-gray-700 cursor-pointer flex items-center gap-2"
								>
									{ __( 'Show Email Addresses', 'author-profile-blocks' ) }
									<Badge variant="destructive" className="text-xs">
										{ __( 'Privacy', 'author-profile-blocks' ) }
									</Badge>
								</Label>
								<p className="text-xs text-gray-400 mt-0.5">
									{ __( 'Warning: displaying email addresses publicly may increase spam.', 'author-profile-blocks' ) }
								</p>
							</div>
						</div>

						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>

				{ /* Performance: Cache Duration */ }
				<TabsContent value="performance">
					<div className="bg-white rounded-xl border border-gray-200 p-5">
						<h2 className="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-4">
							{ __( 'Performance Settings', 'author-profile-blocks' ) }
						</h2>
						<div className="space-y-1.5">
							<Label className="text-sm font-medium text-gray-700">
								{ __( 'Cache Duration (hours)', 'author-profile-blocks' ) }
							</Label>
							<p className="text-xs text-gray-400">
								{ __( 'How long to cache author data in hours (1–168). Default: 24.', 'author-profile-blocks' ) }
							</p>
							<Input
								type="number"
								min={ 1 }
								max={ 168 }
								value={ settings.cache_duration }
								className="w-32"
								onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
									setSettings( ( s: Settings ) => ( {
										...s,
										cache_duration: parseInt( e.target.value, 10 ) || 24,
									} ) )
								}
							/>
						</div>
						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>
			</Tabs>
		</div>
	);
}
```

- [ ] **Step 2: Commit**

```bash
git add src/admin/components/Pages/SettingsPage.tsx
git commit -m "feat(admin): add tabbed SettingsPage (General/Display/Performance)"
```

---

### Task 11: Create PluginsPage

**Files:**
- Create: `src/admin/components/Pages/PluginsPage.tsx`

- [ ] **Step 1: Create src/admin/components/Pages/PluginsPage.tsx**

```tsx
import { useState, useEffect } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import { ExternalLink, Star } from 'lucide-react';
import type { WPPlugin } from '../../types';

export default function PluginsPage() {
	const [ plugins, setPlugins ] = useState< WPPlugin[] >( [] );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState< string | null >( null );

	useEffect( () => {
		fetch(
			'https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[author]=mralaminahamed&request[per_page]=20'
		)
			.then( ( r ) => r.json() )
			.then( ( data: { plugins?: WPPlugin[] } ) => {
				setPlugins(
					( data.plugins ?? [] ).filter( ( p ) => p.slug !== 'author-profile-blocks' )
				);
				setLoading( false );
			} )
			.catch( () => {
				setError(
					__( 'Could not load plugins. Check your internet connection.', 'author-profile-blocks' )
				);
				setLoading( false );
			} );
	}, [] );

	return (
		<div className="p-6">
			<div className="mb-6">
				<h1 className="text-2xl font-bold text-gray-900">
					{ __( 'Our Plugins', 'author-profile-blocks' ) }
				</h1>
				<p className="text-sm text-gray-500 mt-1">
					{ __( 'Other plugins by the same author on WordPress.org.', 'author-profile-blocks' ) }
				</p>
			</div>

			{ loading && (
				<div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
					{ Array.from( { length: 6 } ).map( ( _, i ) => (
						<div
							key={ i }
							className="bg-white rounded-xl border border-gray-200 p-5 animate-pulse"
						>
							<div className="flex items-center gap-3 mb-3">
								<div className="w-10 h-10 rounded-lg bg-gray-100" />
								<div className="flex-1 space-y-1.5">
									<div className="h-3 bg-gray-100 rounded w-3/4" />
									<div className="h-2.5 bg-gray-100 rounded w-1/4" />
								</div>
							</div>
							<div className="space-y-1.5">
								<div className="h-2.5 bg-gray-100 rounded" />
								<div className="h-2.5 bg-gray-100 rounded w-5/6" />
							</div>
						</div>
					) ) }
				</div>
			) }

			{ error && (
				<div className="rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
					{ error }
				</div>
			) }

			{ ! loading && ! error && plugins.length === 0 && (
				<p className="text-sm text-gray-400 italic">
					{ __( 'No plugins found.', 'author-profile-blocks' ) }
				</p>
			) }

			{ ! loading && ! error && plugins.length > 0 && (
				<div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
					{ plugins.map( ( plugin ) => (
						<PluginCard key={ plugin.slug } plugin={ plugin } />
					) ) }
				</div>
			) }
		</div>
	);
}

function PluginCard( { plugin }: { plugin: WPPlugin } ) {
	const icon = plugin.icons?.svg ?? plugin.icons?.[ '2x' ] ?? plugin.icons?.[ '1x' ];
	const stars = Math.round( plugin.rating / 20 );

	return (
		<div className="bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
			<div className="flex items-center gap-3">
				{ icon ? (
					<img
						src={ icon }
						alt={ plugin.name }
						className="w-10 h-10 rounded-lg object-cover"
					/>
				) : (
					<div className="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
						{ plugin.name.charAt( 0 ) }
					</div>
				) }
				<div className="min-w-0">
					<h3 className="text-sm font-semibold text-gray-900 truncate">
						{ decodeEntities( plugin.name ) }
					</h3>
					<p className="text-xs text-gray-400">v{ plugin.version }</p>
				</div>
			</div>

			<p className="text-sm text-gray-600 line-clamp-3 flex-1">
				{ decodeEntities( plugin.short_description ) }
			</p>

			<div className="flex items-center justify-between">
				<div className="flex items-center gap-0.5">
					{ Array.from( { length: 5 } ).map( ( _, i ) => (
						<Star
							key={ i }
							className={ `w-3.5 h-3.5 ${ i < stars
								? 'text-yellow-400 fill-yellow-400'
								: 'text-gray-200 fill-gray-200'
							}` }
						/>
					) ) }
					<span className="text-xs text-gray-400 ml-1">
						({ plugin.num_ratings })
					</span>
				</div>
				<span className="text-xs text-gray-400">
					{ plugin.active_installs.toLocaleString() }+{ ' ' }
					{ __( 'active', 'author-profile-blocks' ) }
				</span>
			</div>

			<a
				href={ `https://wordpress.org/plugins/${ plugin.slug }/` }
				target="_blank"
				rel="noopener noreferrer"
				className="flex items-center justify-center gap-1.5 w-full text-sm font-medium text-blue-600 hover:text-blue-700 py-2 px-3 rounded-lg border border-blue-200 hover:bg-blue-50 transition-colors"
			>
				<ExternalLink className="w-3.5 h-3.5" />
				{ __( 'View on WordPress.org', 'author-profile-blocks' ) }
			</a>
		</div>
	);
}
```

- [ ] **Step 2: Commit**

```bash
git add src/admin/components/Pages/PluginsPage.tsx
git commit -m "feat(admin): add PluginsPage with WP.org plugin grid"
```

---

### Task 12: Create AboutPage

**Files:**
- Create: `src/admin/components/Pages/AboutPage.tsx`

- [ ] **Step 1: Create src/admin/components/Pages/AboutPage.tsx**

```tsx
import { __ } from '@wordpress/i18n';
import { ExternalLink, GitBranch, Info, Heart } from 'lucide-react';

const GITHUB_URL = 'https://github.com/mralaminahamed/author-profile-blocks';
const DOCS_URL = `${ GITHUB_URL }#readme`;
const SUPPORT_URL = `${ GITHUB_URL }/issues`;

export default function AboutPage() {
	const version = window.apblAdmin.version;

	return (
		<div className="p-6 max-w-2xl">
			<div className="mb-6">
				<h1 className="text-2xl font-bold text-gray-900">
					{ __( 'About', 'author-profile-blocks' ) }
				</h1>
				<p className="text-sm text-gray-500 mt-1">
					{ __( 'Plugin information and useful links.', 'author-profile-blocks' ) }
				</p>
			</div>

			<div className="space-y-4">
				<div className="bg-white rounded-xl border border-gray-200 p-5">
					<div className="flex items-start gap-3 mb-4">
						<div className="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
							<Info className="w-5 h-5 text-blue-500" />
						</div>
						<div>
							<p className="text-sm font-semibold text-gray-900">
								{ __( 'Author Profile Blocks', 'author-profile-blocks' ) }
							</p>
							<p className="text-xs text-gray-400">
								{ __( 'Version', 'author-profile-blocks' ) } { version }
							</p>
							<p className="text-xs text-gray-500 mt-1">
								{ __( 'A collection of powerful Gutenberg blocks for showcasing author profiles and team members.', 'author-profile-blocks' ) }
							</p>
						</div>
					</div>
					<div className="flex flex-wrap gap-2">
						<a
							href={ GITHUB_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900 border border-gray-200 rounded-md px-3 py-1.5 hover:border-gray-300 transition-colors"
						>
							<GitBranch className="w-3.5 h-3.5" />
							{ __( 'GitHub', 'author-profile-blocks' ) }
						</a>
						<a
							href={ DOCS_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900 border border-gray-200 rounded-md px-3 py-1.5 hover:border-gray-300 transition-colors"
						>
							<ExternalLink className="w-3.5 h-3.5" />
							{ __( 'Documentation', 'author-profile-blocks' ) }
						</a>
						<a
							href={ SUPPORT_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900 border border-gray-200 rounded-md px-3 py-1.5 hover:border-gray-300 transition-colors"
						>
							<ExternalLink className="w-3.5 h-3.5" />
							{ __( 'Support', 'author-profile-blocks' ) }
						</a>
					</div>
				</div>

				<div className="bg-white rounded-xl border border-gray-200 p-5">
					<div className="flex items-center gap-3">
						<div className="w-9 h-9 rounded-lg bg-pink-50 flex items-center justify-center shrink-0">
							<Heart className="w-5 h-5 text-pink-500" />
						</div>
						<div>
							<p className="text-sm font-semibold text-gray-900">Al Amin Ahamed</p>
							<a
								href="https://github.com/mralaminahamed"
								target="_blank"
								rel="noopener noreferrer"
								className="text-xs text-blue-500 hover:underline"
							>
								@mralaminahamed
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
```

- [ ] **Step 2: Commit**

```bash
git add src/admin/components/Pages/AboutPage.tsx
git commit -m "feat(admin): add AboutPage with plugin info, links, author card"
```

---

### Task 13: Remove .gitkeep stubs replaced by real files

**Files:**
- Delete: `src/admin/hooks/.gitkeep`
- Delete: `src/admin/pages/.gitkeep`

- [ ] **Step 1: Remove stubs**

```bash
git rm src/admin/hooks/.gitkeep src/admin/pages/.gitkeep
git commit -m "chore: remove .gitkeep stubs replaced by real files"
```

---

### Task 14: Verify build + type check

**Files:** none — verification only

- [ ] **Step 1: Run full build**

```bash
yarn build 2>&1 | tail -6
```

Expected: `webpack X.X.X compiled successfully` with no errors. Watch for:
- No `Cannot find module` errors
- `build/admin/index.js` and `build/admin/style-index.css` present

- [ ] **Step 2: Verify admin build output**

```bash
ls build/admin/
```

Expected: `index.js  index.asset.php  style-index.css  style-index-rtl.css`

- [ ] **Step 3: Verify block output untouched**

```bash
ls build/blocks/
```

Expected: `author-carousel/  author-grid/  author-list/  author-profile/`

- [ ] **Step 4: Run TypeScript check**

```bash
npx tsc --noEmit 2>&1 | head -20
```

Expected: no output (zero errors). If errors appear, fix them before proceeding.

- [ ] **Step 5: Check asset.php has correct deps**

```bash
cat build/admin/index.asset.php
```

Expected: dependencies array includes `wp-api-fetch`, `wp-element`, `wp-i18n`, `wp-html-entities`. These are auto-generated by `@wordpress/dependency-extraction-webpack-plugin`.

- [ ] **Step 6: Final commit if any fixes were needed**

```bash
git status
# Only commit if there were fixup changes
```
