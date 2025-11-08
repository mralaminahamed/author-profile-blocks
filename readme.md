# Author Profile Blocks

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](http://www.gnu.org/licenses/gpl-2.0.txt)
[![Gutenberg Compatible](https://img.shields.io/badge/Gutenberg-Ready-00A0D2.svg)](https://wordpress.org/gutenberg/)

> **Modern Gutenberg blocks for WordPress author profiles.**
>
> 📖 **[Documentation Site](https://mralaminahamed.github.io/author-profile-blocks/)** | 🐛 **[Report Issues](https://github.com/mralaminahamed/author-profile-blocks/issues)** | 💬 **[Community Support](https://wordpress.org/support/plugin/author-profile-blocks/)**

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

## 📚 Documentation

📖 **[Full Documentation Site](https://mralaminahamed.github.io/author-profile-blocks/)** - Complete user guides, API reference, and developer documentation.

### Getting Started

- **[Installation Guide](./docs/getting-started.md)** - Complete setup and configuration
- **[User Profiles](./docs/user-profiles.md)** - Managing author information
- **[Customization](./docs/customization.md)** - Styling and configuration options

### Block Documentation

- **[Author Profile](./docs/blocks/author-profile.md)** - Single author block implementation
- **[Author Grid](./docs/blocks/author-grid.md)** - Multi-author grid functionality
- **[Author Carousel](./docs/blocks/author-carousel.md)** - Interactive carousel features
- **[Author List](./docs/blocks/author-list.md)** - Filtered list capabilities

### Developer Resources

- **[Developer API](./docs/developer-api.md)** - Complete hooks and filters reference
- **[Plugin Architecture](./docs/plugin-architecture.md)** - Technical implementation details
- **[Contributing](./docs/contributing.md)** - Development guidelines and standards
- **[Performance Guide](./docs/performance-guide.md)** - Optimization and caching strategies

### Additional Resources

- **[FAQ](./docs/faq.md)** - Frequently asked questions
- **[Troubleshooting](./docs/troubleshooting.md)** - Common issues and solutions
- **[Advanced Examples](./docs/advanced-examples.md)** - Code examples and use cases
- **[Changelog](./docs/changelog.md)** - Version history and updates

## 🛠️ Development

### Quick Start

```bash
# Install dependencies
composer install && yarn install

# Development build with watch mode
yarn start

# Production build
yarn build

# Run tests
composer test && yarn test:e2e
```

### Code Quality

```bash
# PHP standards and static analysis
composer phpcs && composer phpstan

# JavaScript/TypeScript linting
yarn lint

# Full test suite
composer test && yarn test:e2e
```

## 🤝 Contributing

We welcome contributions! Please see our **[Contributing Guide](./docs/contributing.md)** for development guidelines and coding standards.

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes following WordPress coding standards
4. Test thoroughly (PHP, JavaScript, and manual testing)
5. Submit a pull request with detailed description

## 📞 Support

For support, feature requests, or bug reports:

- **📖 Documentation**: [Complete User Guide](./docs/)
- **💬 Community Support**: [WordPress.org Forums](https://wordpress.org/support/plugin/author-profile-blocks/)
- **🐛 Bug Reports**: [GitHub Issues](https://github.com/mralaminahamed/author-profile-blocks/issues)
- **💡 Feature Requests**: [GitHub Discussions](https://github.com/mralaminahamed/author-profile-blocks/discussions)

## 📜 License

GPL v2 or later - See [LICENSE](./LICENSE) for details.

## 👨‍💻 Credits

Developed by [Al Amin Ahamed](https://github.com/mralaminahamed) for the WordPress community.
