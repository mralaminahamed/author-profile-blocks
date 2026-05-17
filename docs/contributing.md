---
layout: default
title: Contributing
nav_order: 8
permalink: /contributing/
---

# Contributing to Author Profile Blocks
{: .no_toc }

Guidelines for contributing to the Author Profile Blocks plugin development.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Prerequisites

- PHP 7.4+, Node.js 20+, Yarn 4 (via Corepack), Composer 2
- WordPress local environment (e.g. [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/), LocalWP, or Valet)
- Basic familiarity with WordPress Gutenberg blocks and React

---

## Setting Up

1. **Fork and clone**

   ```bash
   git clone https://github.com/YOUR-USERNAME/author-profile-blocks.git
   cd author-profile-blocks
   ```

2. **Enable Corepack** (required for Yarn 4)

   ```bash
   corepack enable
   ```

3. **Install dependencies**

   ```bash
   yarn install
   composer install
   ```

4. **Start the dev watcher**

   ```bash
   yarn start
   ```

5. **Build for production**

   ```bash
   yarn build
   ```

---

## Branch Strategy

| Branch | Purpose |
|---|---|
| `trunk` | Stable production branch; all PRs target this |
| `feature/*` | New features — branch from `trunk` |
| `fix/*` | Bug fixes — branch from `trunk` |

---

## Making Changes

1. Create a branch

   ```bash
   git checkout trunk && git pull origin trunk
   git checkout -b fix/carousel-autoplay
   ```

2. Develop — follow the coding standards below.

3. Commit using [Conventional Commits](https://www.conventionalcommits.org/):

   ```
   feat(carousel): add autoplay pause on hover
   fix(list): correct gap between list items
   docs(readme): update tested-up-to to 6.7
   chore(ci): enable corepack before setup-node
   ```

4. Push and open a PR against `trunk`.

---

## Coding Standards

### PHP

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- PHP 7.4+ syntax; PHP 8.x compatible
- All public API surfaces must have PHPDoc blocks
- Sanitize all input; escape all output

### JavaScript / React

- Follow [WordPress JS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Functional components + hooks; no class components
- Use `@wordpress/*` packages before reaching for third-party libraries
- JSDoc for exported functions and components

### SCSS

- Use the modern module system: `@use`, `@forward` — **never** `@import`
- Use `sass:color` and `sass:math` — **never** deprecated `lighten()`/`darken()`
- All class names use the `apbl-` prefix
- No `@extend` — use mixins or shared classes instead
- Variables live in `src/scss/common/_variables.scss`
- Mixins live in `src/scss/common/_mixins.scss`

---

## Project Structure

```
author-profile-blocks/
├── includes/
│   └── Blocks/
│       ├── Concerns/                  # 7 traits extracted from AuthorBlockBase
│       │   ├── BuildsBlockClasses.php
│       │   ├── BuildsBlockStyles.php
│       │   ├── HasRenderCache.php
│       │   ├── ProvidesMessages.php
│       │   ├── RendersComponents.php
│       │   ├── RendersLayouts.php
│       │   └── ResolvesAuthorData.php
│       ├── AuthorBlockBase.php        # shared render logic (uses traits)
│       ├── AuthorProfileBlock.php
│       ├── AuthorGridBlock.php
│       ├── AuthorCarouselBlock.php
│       └── AuthorListBlock.php
├── src/
│   ├── admin/                         # React + shadcn/ui admin SPA
│   ├── blocks/
│   │   ├── author-profile/
│   │   ├── author-grid/
│   │   ├── author-carousel/
│   │   │   └── view.js                # Slick carousel init (frontend)
│   │   └── author-list/
│   └── supports/
│       ├── js/components/inspector/   # shared inspector panel components
│       ├── js/hooks/                  # shared React hooks (e.g. useGoogleFont)
│       └── scss/common/               # shared design tokens + mixins
├── templates/blocks/                  # PHP render templates
└── docs/                              # Jekyll documentation site
```

---

## Building and Packaging

```bash
yarn build          # production build
yarn start          # dev watcher
```

The build output lands in `build/`. The `.distignore` file controls what's excluded from the WordPress.org release zip.

---

## Testing

Manual testing checklist before submitting a PR:

1. All four blocks insert and render in the editor without JS errors
2. Frontend renders correctly (check browser console for errors)
3. Carousel initializes: slides, arrows, dots functional
4. Author image alignment matches selected text-align setting
5. Social profile links open correct URLs
6. Responsive layout looks correct at 320 px, 768 px, and 1280 px

---

## Submitting a Pull Request

1. Verify your branch is up to date with `trunk`
2. Describe **what** changed and **why** in the PR body
3. Link any related issues (`Fixes #123`)
4. Add a changelog entry in `docs/changelog.md`
5. Ensure `yarn build` completes without errors

---

## Release Process

1. Bump version in `author-profile-blocks.php`, `package.json`, `composer.json`, and `readme.txt`
2. Update `docs/changelog.md`
3. Merge to `trunk`
4. Create an annotated git tag: `git tag -a v1.x.x -m "Release v1.x.x"`
5. Push trunk + tag — the GitHub Actions workflow handles the WordPress.org SVN deploy and GitHub release

---

## License

By contributing, you agree your contributions will be licensed under **GPL-2.0-or-later**.
