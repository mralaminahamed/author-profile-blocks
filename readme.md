# Author Profile Blocks

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](http://www.gnu.org/licenses/gpl-2.0.txt)
[![Gutenberg Compatible](https://img.shields.io/badge/Gutenberg-Ready-00A0D2.svg)](https://wordpress.org/gutenberg/)

> **Modern Gutenberg blocks for WordPress author profiles. PHP 7.4+, WordPress 6.0+, full TypeScript/React implementation.**

## 🔧 Technical Specifications

- **WordPress**: 6.0+ (tested up to 6.8)
- **PHP**: 7.4+ (tested up to PHP 8.3)
- **Gutenberg**: Required (block editor)
- **Architecture**: PSR-4 autoloading, modern PHP patterns
- **Frontend**: React/TypeScript, modern build system
- **Testing**: PHPUnit, Playwright E2E
- **Code Quality**: PHPCS, PHPStan, ESLint, Stylelint

## 📦 Installation

```bash
# Clone repository
git clone https://github.com/mralaminahamed/author-profile-blocks.git

# Install PHP dependencies
composer install

# Install Node dependencies
yarn install

# Build assets
yarn build

# Activate plugin
wp plugin activate author-profile-blocks
```

## 🏗️ Architecture

### Block Structure

```
src/blocks/
├── author-profile/     # Single author block
├── author-grid/        # Multi-author grid
├── author-carousel/    # Interactive carousel
└── author-list/        # Filtered list view
```

### PHP Classes

```php
AuthorProfileBlocks\Blocks\Author_Profile_Block
AuthorProfileBlocks\Blocks\Author_Grid_Block
AuthorProfileBlocks\Blocks\Author_Carousel_Block
AuthorProfileBlocks\Blocks\Author_List_Block
```

### Key Components

- **Block Registration**: `block.json` configuration
- **Server-Side Rendering**: PHP-based block output
- **Client-Side Editing**: React components for Gutenberg
- **User Meta Integration**: Extended profile fields
- **Performance Caching**: Query optimization and transients

## 🔌 Developer API

### Hook System

**Action Hooks:**

```php
// Block registration
do_action('apbl_register_blocks', $blocks);

// User profile saving
do_action('apbl_user_profile_saved', $user_id, $data);

// Block rendering
do_action('apbl_before_block_render', $block_name, $attributes);
```

**Filter Hooks:**

```php
// Modify block attributes
$attributes = apply_filters('apbl_block_attributes', $attributes, $block_name);

// Modify user query
$query_args = apply_filters('apbl_user_query_args', $query_args, $block_attributes);

// Modify rendered output
$content = apply_filters('apbl_block_content', $content, $block_name, $attributes);
```

### Template System

```php
// Override block templates
add_filter('apbl_template_paths', function($paths) {
    $paths[] = get_stylesheet_directory() . '/author-profile-blocks/';
    return $paths;
});
```

### Data Architecture

**User Meta Storage:**

```php
// Core fields
'apbl_position' => string      // Job title/position
'apbl_description' => string   // Extended biography
'apbl_social_links' => array   // Social media URLs
'apbl_member_since' => string  // Custom join date
```

**Block Configuration:**

```json
{
	"name": "author-profile-blocks/author-profile",
	"attributes": {
		"authorId": { "type": "number", "default": 0 },
		"layout": { "type": "string", "default": "card" },
		"showSocial": { "type": "boolean", "default": true }
	}
}
```

## 🛠️ Development

### Build System

```bash
# Development
yarn start          # Watch mode with HMR
yarn build          # Production build
yarn build:dev      # Development build

# Code Quality
yarn lint           # ESLint + Stylelint
yarn lint:fix       # Auto-fix issues
yarn type-check     # TypeScript validation
```

### PHP Quality

```bash
# Code Standards
composer phpcs      # Check PHP standards
composer phpcbf     # Fix PHP issues
composer phpstan    # Static analysis

# Testing
composer test       # Run PHPUnit tests
composer test:e2e   # Run Playwright tests

# Translations
composer makepot    # Generate .pot file
```

### Block Development

```javascript
// Register block
registerBlockType("author-profile-blocks/author-profile", {
	title: "Author Profile",
	category: "widgets",
	attributes: {
		authorId: { type: "number", default: 0 },
	},
	edit: AuthorProfileEdit,
	save: () => null, // Dynamic rendering
});
```

## 📚 Documentation

### Developer Resources

- **[Block API](./docs/developer-api.md)** - Complete hooks and filters reference
- **[Architecture](./docs/plugin-architecture.md)** - Technical implementation details
- **[Contributing](./docs/contributing.md)** - Development guidelines and standards
- **[Performance](./docs/performance-guide.md)** - Optimization and caching strategies

### Block Documentation

- **[Author Profile](./docs/blocks/author-profile.md)** - Single author block implementation
- **[Author Grid](./docs/blocks/author-grid.md)** - Multi-author grid functionality
- **[Author Carousel](./docs/blocks/author-carousel.md)** - Interactive carousel features
- **[Author List](./docs/blocks/author-list.md)** - Filtered list capabilities

---

## 🛠️ Development

**Asset Compilation:**

```bash
yarn install          # Install dependencies
yarn start            # Development build with watch mode
yarn build            # Production build
yarn lint             # Code quality checks
```

**PHP Quality:**

```bash
composer install      # Install PHP dependencies
composer phpcs        # Run PHP CodeSniffer
composer phpstan      # Static analysis
composer test         # Run test suite
```

**Package Creation:**

```bash
yarn plugin-zip       # Create distribution ZIP file
```

## 🔧 Developer API

### Hook System

**Action Hooks:**

```php
// Block rendering
do_action('apbl_before_author_render', $author_id, $block_attributes);
do_action('apbl_after_author_render', $author_id, $block_attributes);

// User profile
do_action('apbl_user_profile_fields_saved', $user_id, $fields);
```

**Filter Hooks:**

```php
// Modify author data
$author_data = apply_filters('apbl_author_data', $author_data, $author_id);

// Modify block attributes
$attributes = apply_filters('apbl_block_attributes', $attributes, $block_type);

// Modify social links
$social_links = apply_filters('apbl_social_links', $social_links, $author_id);

// Modify available layouts
$layouts = apply_filters('apbl_available_layouts', $layouts, $block_type);
```

### Template System

```php
// Template loading pattern
apbl_get_template('author-card.php', $args);

// Override in your theme
yourtheme/author-profile-blocks/author-card.php
```

### Data Architecture

**User Meta Storage:**

```php
// Position/Title
update_user_meta($user_id, 'apbl_position', $position);

// Extended Description
update_user_meta($user_id, 'apbl_description', $description);

// Social Profiles
update_user_meta($user_id, 'apbl_social_links', $social_data);
```

**Block Configuration:**

```json
{
	"name": "author-profile-blocks/author-profile",
	"attributes": {
		"authorId": { "type": "number", "default": 0 },
		"layout": { "type": "string", "default": "card" },
		"showEmail": { "type": "boolean", "default": true }
	}
}
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes following WordPress coding standards
4. Test thoroughly (PHP, JavaScript, and manual testing)
5. Submit a pull request with detailed description

### Development Guidelines

- Follow WordPress Coding Standards (PHPCS)
- Write comprehensive tests for new features
- Update documentation for API changes
- Maintain backward compatibility
- Test across multiple WordPress versions

## 📝 Version

**Current Version:** v1.0.0 - Production Ready

See [CHANGELOG.md](./CHANGELOG.md) for complete version history and updates.

## 📞 Support

For support, feature requests, or bug reports:

- **WordPress.org**: [Plugin Support Forum](https://wordpress.org/support/plugin/author-profile-blocks/)
- **GitHub Issues**: [Report Bugs](https://github.com/mralaminahamed/author-profile-blocks/issues)
- **Documentation**: [User Guide](./docs/)

## 📜 License

GPL v2 or later - See [LICENSE](./LICENSE) for details.

## 👨‍💻 Credits

Developed by Al Amin Ahamed for the WordPress community.

**Contributors:** [mralaminahamed](https://profiles.wordpress.org/mralaminahamed/)
**Tags:** block, author, profile, gutenberg, team, user, biography, team-members
**Tested up to:** 6.8
**Stable tag:** 1.0.0
**Requires at least:** 6.0
**Requires PHP:** 7.4

## 🧪 Testing

### Unit Tests

```bash
composer test                    # Run PHPUnit tests
composer test:coverage          # Generate coverage report
```

### E2E Tests

```bash
yarn test:e2e                   # Run Playwright tests
yarn test:e2e:headed           # Run with browser UI
```

### Code Quality

```bash
# PHP
composer phpcs                  # PHP CodeSniffer
composer phpstan               # Static analysis
composer phpmd                 # Mess detector

# JavaScript/TypeScript
yarn lint                      # ESLint + Stylelint
yarn type-check                # TypeScript validation
```

## 📋 Changelog

### v1.0.0

- **Core Architecture**: PSR-4 autoloading, modern PHP patterns
- **Block System**: Four Gutenberg blocks with React/TypeScript
- **User Integration**: Extended profile fields without custom post types
- **Build System**: Webpack-based asset compilation
- **Testing Suite**: PHPUnit + Playwright E2E coverage
- **Documentation**: Comprehensive developer guides
- **Performance**: Query optimization and caching layer

## 🤝 Contributing

1. **Fork** the repository
2. **Clone** your fork: `git clone https://github.com/your-username/author-profile-blocks.git`
3. **Create** feature branch: `git checkout -b feature/amazing-feature`
4. **Install** dependencies: `composer install && yarn install`
5. **Develop** with standards: `yarn lint && composer phpcs`
6. **Test** thoroughly: `composer test && yarn test:e2e`
7. **Commit** changes: `git commit -m "feat: add amazing feature"`
8. **Push** branch: `git push origin feature/amazing-feature`
9. **Create** Pull Request

### Development Standards

- **PHP**: PSR-12, WordPress Coding Standards
- **JavaScript**: ESLint, Prettier configuration
- **SCSS**: Stylelint, BEM-like naming
- **Git**: Conventional commits, feature branches
- **Testing**: 80%+ code coverage required
- **Documentation**: Update docs for API changes

## 📜 License

GPL v2 or later - See [LICENSE](./LICENSE) for details.

## 👨‍💻 Credits

Developed by Al Amin Ahamed for the WordPress community.

**Contributors:** [mralaminahamed](https://profiles.wordpress.org/mralaminahamed/)
**Tags:** block, author, profile, gutenberg, team, user, biography, team-members
**Tested up to:** 6.8
**Stable tag:** 1.0.0
**Requires at least:** 6.0
**Requires PHP:** 7.4
