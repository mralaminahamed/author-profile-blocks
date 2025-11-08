# Contributing to Author Profile Blocks

📖 **[View Full Contributing Guide on Documentation Site](https://mralaminahamed.github.io/author-profile-blocks/contributing/)**

Thank you for your interest in contributing to Author Profile Blocks! We welcome contributions from the community.

## Development Setup

### Prerequisites

- PHP 7.4+
- Node.js 16+
- Composer
- Yarn
- WordPress 6.0+

### Installation

1. Clone the repository
2. Run `composer install` to install PHP dependencies
3. Run `yarn install` to install Node.js dependencies
4. Run `composer phpcs:check` to ensure coding standards are met

### Development Workflow

1. Create a feature branch from `main`
2. Make your changes following our coding standards
3. Run linting: `composer phpcs:check`
4. Run static analysis: `composer phpstan`
5. Build assets: `yarn build`
6. Test in WordPress environment
7. Submit a pull request

## Coding Standards

### PHP

- Follow WordPress Coding Standards
- Use PSR-4 autoloading
- Namespace: `AuthorProfileBlocks\`
- PHPDoc for all public methods
- Proper error handling with WordPress logging

### JavaScript

- Use WordPress Scripts build system
- React for Gutenberg blocks
- WordPress components for UI
- Proper AJAX with nonces

### CSS/SCSS

- Follow WordPress CSS standards
- SCSS with variables and mixins
- BEM methodology for custom classes

## Testing

- Test WordPress integration thoroughly
- Test Gutenberg block functionality
- Include both positive and negative test cases
- Test with different user roles and capabilities

## Pull Request Process

1. Ensure all linting passes
2. Update documentation if needed
3. Follow conventional commit format
4. Provide clear description of changes
5. Reference related issues
6. Test in multiple WordPress environments

## Reporting Issues

- Use GitHub issues for bug reports
- Include WordPress version and PHP version
- Provide steps to reproduce
- Include browser console errors if applicable
- Test with default WordPress theme (Twenty Twenty-One)

## Block Development

- Follow Gutenberg block development best practices
- Use block.json for block registration
- Implement proper attribute validation
- Ensure responsive design
- Test block in different contexts (posts, pages, patterns)

## License

By contributing, you agree that your contributions will be licensed under GPL-3.0-only.
