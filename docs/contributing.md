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

## Getting Started

Thank you for considering contributing to Author Profile Blocks! This document provides guidelines and instructions for contributing to the plugin's development.

### Prerequisites

Before you begin, you'll need:

- Basic knowledge of PHP, JavaScript, and WordPress development
- Familiarity with WordPress block editor (Gutenberg) concepts
- Git and GitHub experience
- Local WordPress development environment

### Setting Up the Development Environment

1. **Fork the Repository**:
   Visit the [Author Profile Blocks repository](https://github.com/mralaminahamed/author-profile-blocks) and click the "Fork" button in the top-right corner.

2. **Clone Your Fork**:
   ```bash
   git clone https://github.com/YOUR-USERNAME/author-profile-blocks.git
   cd author-profile-blocks
   ```

3. **Install Dependencies**:
   ```bash
   npm install
   composer install
   ```

4. **Start Development Server**:
   ```bash
   npm start
   ```

5. **Build for Production**:
   ```bash
   npm run build
   ```

## Development Workflow

### Branch Organization

- `main` - The production branch containing stable releases
- `develop` - The development branch for upcoming releases
- Feature branches - Create from `develop` for new features
- Bugfix branches - Create from `develop` for bug fixes
- Hotfix branches - Create from `main` for urgent production fixes

### Making Changes

1. **Create a Branch**:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/your-feature-name
   ```

2. **Develop Your Feature**:
    - Make your changes following the coding standards
    - Write tests for your changes when applicable
    - Update documentation as needed

3. **Commit Your Changes**:
   ```bash
   git add .
   git commit -m "Feature: Brief description of your changes"
   ```

   Use semantic commit messages:
    - `Feature:` for new features
    - `Fix:` for bug fixes
    - `Docs:` for documentation changes
    - `Style:` for formatting changes
    - `Refactor:` for code refactoring
    - `Test:` for adding or modifying tests
    - `Chore:` for changes to the build process or auxiliary tools

4. **Push Your Changes**:
   ```bash
   git push origin feature/your-feature-name
   ```

5. **Create a Pull Request**:
    - Go to the GitHub repository
    - Click "New Pull Request"
    - Select `develop` as the base branch and your feature branch as the compare branch
    - Provide a detailed description of your changes

## Coding Standards

### PHP Coding Standards

Author Profile Blocks follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/). Key points:

- Use PHP 7.4+ features where appropriate
- Follow WordPress naming conventions
- Use namespaces for class organization
- Prioritize backward compatibility
- Add proper PHPDoc comments

### JavaScript Coding Standards

For JavaScript code:

- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use ES6+ features with appropriate polyfills
- Modularize code with import/export
- Use React hooks for functional components
- Add JSDoc comments for functions and components

### CSS/SCSS Coding Standards

For styling:

- Follow [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- Use SCSS for preprocessed styles
- Use BEM methodology for class naming
- Create modular and reusable style components
- Ensure responsive design
- Respect theme compatibility

### Documentation Standards

For documentation:

- Document all public methods, hooks, functions, and classes
- Use markdown for documentation files
- Keep examples up-to-date
- Include version numbers for API changes
- Document deprecations clearly

## Testing

### Unit Tests

For PHP unit testing:

```bash
composer test
```

### End-to-End Tests

For end-to-end testing of the blocks:

```bash
npm run test:e2e
```

### Manual Testing Checklist

Before submitting a pull request, manually test:

1. All blocks in the editor
2. Frontend rendering in various themes
3. Responsive behavior on different screen sizes
4. Compatibility with other popular plugins
5. Accessibility features

## Building and Packaging

### Building Assets

To build the JavaScript and CSS assets:

```bash
npm run build
```

### Creating a Release

To create a release package:

```bash
npm run package
```

This will create a zip file in the `dist` directory.

## Documentation

### Code Documentation

- Add PHPDoc comments to all classes, methods, and functions
- Document hooks with examples
- Keep inline code comments current with changes

### User Documentation

- Update the README.md file with significant changes
- Add or update the documentation in the `docs` directory
- Create screenshots for new features or UI changes

## Submitting Pull Requests

When submitting a pull request:

1. **Provide Context**: Explain what the PR accomplishes and why it's needed
2. **Reference Issues**: Link to any related issues (e.g., "Fixes #123")
3. **Include Tests**: Add appropriate tests for your changes
4. **Update Documentation**: Update any affected documentation
5. **Create a Changelog Entry**: Add an entry to the changelog
6. **Follow Pull Request Template**: Fill out all sections of the PR template

## Code Review Process

What to expect during code review:

1. Initial review within 1-2 weeks
2. Feedback from maintainers on code quality, tests, and documentation
3. Requested changes to be addressed before merging
4. Final approval from at least one maintainer required
5. Merge into the appropriate branch by maintainers

## Release Process

The release process follows these steps:

1. Code freeze on `develop` branch
2. Final testing of all features
3. Creating a release branch from `develop`
4. Version bump in package.json, readme.txt, and main plugin file
5. Merging the release branch into `main`
6. Creating a GitHub release with detailed changelog
7. Building and deploying to WordPress.org

## Getting Help

If you need help with your contribution:

- **Questions**: Open an issue with the "question" label
- **Discussions**: Participate in GitHub Discussions
- **WordPress.org**: Post in the plugin's support forum
- **Documentation**: Refer to this contributing guide and other documentation

## Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct:

- Be respectful and inclusive
- Focus on constructive feedback
- Avoid personal attacks or inflammatory language
- Report inappropriate behavior to the maintainers
- Follow the WordPress community guidelines

## Recognition

All contributors will be recognized in these ways:

- Listed in the CONTRIBUTORS.md file
- Mentioned in release notes for significant contributions
- Acknowledged in WordPress.org plugin page credits

## License

By contributing to Author Profile Blocks, you agree that your contributions will be licensed under the GPL v2 or later.

Thank you for contributing to Author Profile Blocks!