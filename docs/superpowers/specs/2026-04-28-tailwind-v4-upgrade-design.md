# Tailwind CSS v4 Upgrade — Design Spec

**Date:** 2026-04-28
**Scope:** Admin UI only (`src/admin/`)
**Approach:** Full manual migration + admin scaffold

---

## 1. Package Changes

### devDependencies

| Package | Change |
|---|---|
| `tailwindcss ^3.4.18` | → `tailwindcss ^4` |
| `@tailwindcss/postcss ^4.2.4` | keep (already present) |
| `@tailwindcss/forms ^0.5.11` | keep (now loaded via `@plugin` in CSS) |
| `autoprefixer ^10.5.0` | **remove** (bundled in v4 via Lightning CSS) |
| `tailwindcss-animate ^1.0.7` | keep (loaded via `@plugin` in CSS) |

### Files deleted
- `tailwind.config.js` — config moves entirely into CSS

---

## 2. PostCSS Config

`postcss.config.js`:
```js
module.exports = {
  plugins: {
    '@tailwindcss/postcss': {},
  },
};
```

Removes `tailwindcss` (v3 plugin) and `autoprefixer`. `@tailwindcss/postcss` handles both.

---

## 3. CSS Entry File

`src/admin/style.css` — single Tailwind v4 entry point:

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

shadcn `neutral` base color palette. `darkMode: 'media'` from old v3 config expressed as `@media (prefers-color-scheme: dark)` in CSS layer.

---

## 4. Admin Scaffold Structure

`@` alias stays at `src/` — shadcn artifacts live at `src/` level, accessible from admin entry.

```
src/
├── admin/
│   ├── style.css      ← Tailwind v4 CSS entry (section 3)
│   ├── index.tsx      ← React entry point, mounts to #apbl-admin-root
│   ├── hooks/         ← admin-specific React hooks
│   └── pages/         ← admin page components
├── components/
│   └── ui/            ← shadcn components added via `npx shadcn add`
├── lib/
│   └── utils.ts       ← shadcn cn() helper (clsx + tailwind-merge)
└── hooks/             ← shared hooks
```

### `src/lib/utils.ts`
```ts
import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}
```

### `src/admin/index.tsx`
```tsx
import { createRoot } from "react-dom/client";
import "./style.css";

const root = document.getElementById("apbl-admin-root");
if (root) {
  createRoot(root).render(<div>Admin Panel</div>);
}
```

---

## 5. Build Config

`webpack.config.js` — add admin entry only. `@` alias stays at `src/` (unchanged):

```js
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
      '@': path.resolve(process.cwd(), 'src'),
    },
  },
};
```

`@/components/ui` → `src/components/ui`, `@/lib/utils` → `src/lib/utils`. Existing blocks unaffected.

---

## 6. components.json (one change)

Clear the stale `tailwind.config` field (file is being deleted):
```json
"tailwind": {
  "config": "",
  "css": "src/admin/style.css",
  ...
}
```

Other paths already correct:
- `tailwind.css`: `src/admin/style.css` ✓
- `aliases.components`: `@/components` → `src/admin/components` ✓
- `aliases.utils`: `@/lib/utils` → `src/admin/lib/utils` ✓
- `aliases.hooks`: `@/hooks` → `src/admin/hooks` ✓

---

## Constraints

- Block SCSS files (`src/blocks/**/style.scss`, `editor.scss`) are untouched — they use pure Sass with no Tailwind
- `tailwindcss-animate` stays in `dependencies` (runtime) not `devDependencies`
- Admin CSS processed via webpack PostCSS loader using the updated `postcss.config.js`
