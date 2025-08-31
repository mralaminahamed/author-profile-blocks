# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Common Development Commands

### Frontend (JavaScript/React)
- `yarn start` - Development build with watch mode
- `yarn build` - Production build
- `yarn lint` - Run all linting (JS, CSS, markdown, package.json)
- `yarn bundle-analyze` - Analyze webpack bundle with analyzer
- `yarn plugin-zip` - Create plugin distribution zip

### Backend (PHP)
- `composer phpcs:check` - Check PHP code standards
- `composer phpcs:fix` - Fix PHP code standards violations
- `composer phpcs:check:review` - Check with WordPress plugin review standards
- `composer phpstan` - Run static analysis
- `composer wp:make-pot` - Generate translation pot file

### Build & Release
- `composer release` - Complete release process (build, lint, analyze, package)

## Architecture Overview

This is a WordPress Gutenberg plugin that provides four block types for displaying author profiles using WordPress users (not custom post types).

### Core Structure
- **Namespace**: `APBL\AuthorProfileBlocks`
- **Main Plugin**: `includes/Plugin.php` - Singleton pattern, registers all services
- **Block Registry**: `includes/Blocks/Block_Registry.php` - Manages block registration
- **User Meta Provider**: `includes/Core/User_Meta_Provider.php` - Extends WP user profiles

### Block Types
1. **Author Profile** (`author-profile-blocks/author-profile`) - Single author display
2. **Author Grid** (`author-profile-blocks/author-grid`) - Multi-author grid layout  
3. **Author Carousel** (`author-profile-blocks/author-carousel`) - Slider/carousel layout
4. **Author List** (`author-profile-blocks/author-list`) - List format display

### Frontend Architecture
- **React Components**: Located in `src/js/components/`
- **Block Components**: Each block has its own `src/blocks/{block-name}/components/` folder
- **Shared Hooks**: `src/js/hooks/` contains reusable data fetching logic
- **API Services**: `src/js/services/api.js` handles WordPress REST API calls
- **Webpack Config**: Custom entry points for individual blocks

### PHP Architecture
- **Block Base Classes**: `includes/Blocks/Block_Base.php` - Common block functionality
- **Author Rendering**: `includes/Common/Author_Renderer.php` - Server-side rendering
- **Cache Management**: `includes/Common/Cache_Manager.php` - Performance optimization
- **Services Layer**: `includes/Services/` - Business logic separation

### Key Features
- Uses WordPress users instead of custom post types
- Extends user profiles with custom fields (position, social links, extended bio)
- Responsive layouts with extensive customization options
- Performance optimization through caching
- Full internationalization support

### Development Notes
- Block registration uses `block.json` files for WordPress 6.0+ compatibility
- Frontend uses WordPress components (`@wordpress/components`, `@wordpress/block-editor`)
- PHP follows WordPress coding standards with PSR-4 autoloading
- CSS uses SCSS with mixins in `src/scss/common/`
- All blocks support alignment (wide, full) and various layout options