# Tailwind CSS v4 Upgrade + Admin Scaffold Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate from Tailwind CSS v3 to v4 and scaffold the `src/admin/` entry point with shadcn-ready structure.

**Architecture:** Delete `tailwind.config.js` entirely — config moves into `src/admin/style.css` using v4 CSS-first config (`@import`, `@theme`, `@plugin`, `@source`). PostCSS uses only `@tailwindcss/postcss`. Webpack gains a new `admin/index` entry. Shared shadcn utilities live at `src/lib/` and `src/components/ui/`, aliases resolve via `@` → `src/`.

**Tech Stack:** Tailwind CSS v4, `@tailwindcss/postcss`, `@tailwindcss/forms`, `tailwindcss-animate`, React 18, TypeScript, webpack 5, `clsx`, `tailwind-merge`

---

## File Map

| Action | Path | Purpose |
|---|---|---|
| Modify | `package.json` | bump tailwindcss → ^4, remove autoprefixer |
| Modify | `postcss.config.js` | replace plugins with @tailwindcss/postcss only |
| Delete | `tailwind.config.js` | replaced by CSS-first config |
| Modify | `components.json` | clear stale `tailwind.config` field |
| Create | `src/admin/style.css` | Tailwind v4 CSS entry with @theme + @plugin |
| Create | `src/admin/index.tsx` | React entry point, mounts to #apbl-admin-root |
| Create | `src/lib/utils.ts` | shadcn cn() helper |
| Create | `src/components/ui/.gitkeep` | stub dir for future shadcn add |
| Create | `src/hooks/.gitkeep` | stub dir for shared hooks |
| Create | `src/admin/hooks/.gitkeep` | stub dir for admin hooks |
| Create | `src/admin/pages/.gitkeep` | stub dir for admin pages |
| Modify | `webpack.config.js` | add admin/index entry |

---

### Task 1: Update package.json

**Files:**
- Modify: `package.json`

- [ ] **Step 1: Edit package.json — bump tailwindcss, remove autoprefixer**

In `devDependencies`, make these changes:
- `"tailwindcss": "^3.4.18"` → `"tailwindcss": "^4"`
- Remove `"autoprefixer": "^10.5.0"` entirely

`@tailwindcss/postcss` is already `^4.2.4` — leave untouched.
`@tailwindcss/forms` stays at `^0.5.11` — v4 compatible, now loaded via `@plugin` in CSS.
`tailwindcss-animate` stays in `dependencies` (not devDependencies) — runtime dep.

The relevant section of `package.json` after edit:
```json
"devDependencies": {
  "@tailwindcss/forms": "^0.5.11",
  "@tailwindcss/postcss": "^4.2.4",
  ...
  "tailwindcss": "^4",
  ...
}
```

`autoprefixer` line must be gone entirely.

- [ ] **Step 2: Install updated dependencies**

```bash
yarn install
```

Expected: no errors, `tailwindcss` v4.x installed, `autoprefixer` removed from `node_modules`.

Verify:
```bash
node -e "console.log(require('./node_modules/tailwindcss/package.json').version)"
```
Expected output: `4.x.x`

- [ ] **Step 3: Commit**

```bash
git add package.json yarn.lock
git commit -m "build(deps): upgrade tailwindcss to v4, drop autoprefixer"
```

---

### Task 2: Update PostCSS config

**Files:**
- Modify: `postcss.config.js`

- [ ] **Step 1: Replace postcss.config.js**

Current content references v3-style `tailwindcss` plugin. Replace entire file:

```js
module.exports = {
	plugins: {
		'@tailwindcss/postcss': {},
	},
};
```

- [ ] **Step 2: Commit**

```bash
git add postcss.config.js
git commit -m "build(postcss): switch to @tailwindcss/postcss v4 plugin"
```

---

### Task 3: Delete tailwind.config.js + clear components.json

**Files:**
- Delete: `tailwind.config.js`
- Modify: `components.json`

- [ ] **Step 1: Delete tailwind.config.js**

```bash
rm tailwind.config.js
```

- [ ] **Step 2: Clear stale config field in components.json**

`components.json` currently has `"config": "tailwind.config.js"` — clear it since the file is gone:

```json
{
  "$schema": "https://ui.shadcn.com/schema.json",
  "style": "new-york",
  "rsc": false,
  "tsx": true,
  "tailwind": {
    "config": "",
    "css": "src/admin/style.css",
    "baseColor": "neutral",
    "cssVariables": true,
    "prefix": ""
  },
  "iconLibrary": "lucide",
  "aliases": {
    "components": "@/components",
    "utils": "@/lib/utils",
    "ui": "@/components/ui",
    "lib": "@/lib",
    "hooks": "@/hooks"
  },
  "registries": {}
}
```

- [ ] **Step 3: Commit**

```bash
git add components.json
git rm tailwind.config.js
git commit -m "build(tailwind): remove v3 tailwind.config.js, clear stale components.json ref"
```

---

### Task 4: Create Tailwind v4 CSS entry file

**Files:**
- Create: `src/admin/style.css`

- [ ] **Step 1: Create src/admin/ directory and style.css**

```bash
mkdir -p src/admin
```

Create `src/admin/style.css`:

```css
@import "tailwindcss";

@plugin "@tailwindcss/forms";
@plugin "tailwindcss-animate";

@source "../components/**/*.{js,ts,jsx,tsx}";
@source "./pages/**/*.{js,ts,jsx,tsx}";
@source "./index.tsx";

@theme {
  --color-background: hsl(var(--background));
  --color-foreground: hsl(var(--foreground));
  --color-primary: hsl(var(--primary));
  --color-primary-foreground: hsl(var(--primary-foreground));
  --color-secondary: hsl(var(--secondary));
  --color-secondary-foreground: hsl(var(--secondary-foreground));
  --color-muted: hsl(var(--muted));
  --color-muted-foreground: hsl(var(--muted-foreground));
  --color-accent: hsl(var(--accent));
  --color-accent-foreground: hsl(var(--accent-foreground));
  --color-destructive: hsl(var(--destructive));
  --color-border: hsl(var(--border));
  --color-input: hsl(var(--input));
  --color-ring: hsl(var(--ring));
  --radius: 0.5rem;
}

@layer base {
  :root {
    --background: 0 0% 100%;
    --foreground: 0 0% 3.9%;
    --primary: 0 0% 9%;
    --primary-foreground: 0 0% 98%;
    --secondary: 0 0% 96.1%;
    --secondary-foreground: 0 0% 9%;
    --muted: 0 0% 96.1%;
    --muted-foreground: 0 0% 45.1%;
    --accent: 0 0% 96.1%;
    --accent-foreground: 0 0% 9%;
    --destructive: 0 84.2% 60.2%;
    --border: 0 0% 89.8%;
    --input: 0 0% 89.8%;
    --ring: 0 0% 3.9%;
  }

  @media (prefers-color-scheme: dark) {
    :root {
      --background: 0 0% 3.9%;
      --foreground: 0 0% 98%;
      --primary: 0 0% 98%;
      --primary-foreground: 0 0% 9%;
      --secondary: 0 0% 14.9%;
      --secondary-foreground: 0 0% 98%;
      --muted: 0 0% 14.9%;
      --muted-foreground: 0 0% 63.9%;
      --accent: 0 0% 14.9%;
      --accent-foreground: 0 0% 98%;
      --destructive: 0 62.8% 30.6%;
      --border: 0 0% 14.9%;
      --input: 0 0% 14.9%;
      --ring: 0 0% 83.1%;
    }
  }
}
```

- [ ] **Step 2: Commit**

```bash
git add src/admin/style.css
git commit -m "feat(admin): add Tailwind v4 CSS entry with shadcn neutral theme"
```

---

### Task 5: Scaffold src/lib/ and directory stubs

**Files:**
- Create: `src/lib/utils.ts`
- Create: `src/components/ui/.gitkeep`
- Create: `src/hooks/.gitkeep`
- Create: `src/admin/hooks/.gitkeep`
- Create: `src/admin/pages/.gitkeep`

- [ ] **Step 1: Create src/lib/utils.ts**

```bash
mkdir -p src/lib src/components/ui src/hooks src/admin/hooks src/admin/pages
```

Create `src/lib/utils.ts`:

```ts
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn( ...inputs: ClassValue[] ) {
	return twMerge( clsx( inputs ) );
}
```

- [ ] **Step 2: Create .gitkeep files for empty stub dirs**

```bash
touch src/components/ui/.gitkeep
touch src/hooks/.gitkeep
touch src/admin/hooks/.gitkeep
touch src/admin/pages/.gitkeep
```

- [ ] **Step 3: Commit**

```bash
git add src/lib/utils.ts src/components/ui/.gitkeep src/hooks/.gitkeep src/admin/hooks/.gitkeep src/admin/pages/.gitkeep
git commit -m "feat(admin): scaffold src/lib/utils.ts and directory stubs for shadcn"
```

---

### Task 6: Create admin React entry point

**Files:**
- Create: `src/admin/index.tsx`

- [ ] **Step 1: Create src/admin/index.tsx**

```tsx
import { createRoot } from 'react-dom/client';
import './style.css';

const root = document.getElementById( 'apbl-admin-root' );
if ( root ) {
	createRoot( root ).render( <div>Admin Panel</div> );
}
```

- [ ] **Step 2: Commit**

```bash
git add src/admin/index.tsx
git commit -m "feat(admin): add React entry point mounting to #apbl-admin-root"
```

---

### Task 7: Update webpack.config.js

**Files:**
- Modify: `webpack.config.js`

- [ ] **Step 1: Add admin/index entry to webpack config**

Replace the entire `webpack.config.js` with:

```js
/**
 * External dependencies
 */
const path = require( 'path' );

/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const updatedConfig = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry,
		'admin/index': './src/admin/index.tsx',
	},

	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			'@': path.resolve( process.cwd(), 'src' ),
		},
	},
};

module.exports = updatedConfig;
```

- [ ] **Step 2: Commit**

```bash
git add webpack.config.js
git commit -m "build(webpack): add admin/index entry point"
```

---

### Task 8: Verify build

**Files:** none — verification only

- [ ] **Step 1: Run production build**

```bash
yarn build
```

Expected: exits 0, no errors. Watch for:
- No `Cannot find module 'tailwindcss'` errors
- No `tailwind.config.js not found` warnings
- `build/admin/index.js` present in output

```bash
ls build/admin/
```

Expected output includes: `index.js`, `index.asset.php`

- [ ] **Step 2: Verify CSS output**

CSS may be inlined into JS by webpack or emitted separately depending on `mini-css-extract-plugin` config. Confirm at least one of:

```bash
# Separate CSS file
ls build/admin/*.css 2>/dev/null && echo "CSS file found" || echo "CSS inlined in JS"
```

- [ ] **Step 3: Confirm existing blocks still build**

```bash
ls build/blocks/
```

Expected: `author-carousel/`, `author-grid/`, `author-list/`, `author-profile/` directories present — no breakage to existing block output.

- [ ] **Step 4: Commit**

No files changed — nothing to commit. If build produced any generated artifacts tracked by git, add and commit them.
