# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-11-07

### Added

- Initial release of Author Profile Blocks
- Author Profile Block - Display individual author profiles with customizable layouts
- Author Grid Block - Display multiple authors in a responsive grid layout
- Author List Block - Display authors in a clean list format
- Author Carousel Block - Interactive carousel for showcasing author profiles
- Gutenberg block-based architecture with block.json registration
- WordPress REST API integration for author data
- Responsive design with mobile-first approach
- Admin settings and configuration options
- Comprehensive block customization options (layout, styling, content)
- WordPress user integration for author data
- Block preview functionality in editor
- Accessibility features and keyboard navigation

### Changed

- Migrated from legacy shortcode system to modern Gutenberg blocks
- Updated build system to use WordPress Scripts
- Improved code organization with PSR-4 autoloading

### Technical Details

- PHP 7.4+ compatibility
- WordPress 6.0+ compatibility
- PSR-4 namespace: `AuthorProfileBlocks\`
- WordPress Scripts build system
- PHPCS code standards compliance
- PHPStan static analysis
- ESLint and Stylelint for JavaScript/CSS
- Responsive SCSS with variables and mixins