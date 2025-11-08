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

#### Prerequisites

Before setting up the development environment, ensure you have:

- **Node.js**: Version 16+ (LTS recommended)
- **npm**: Version 7+ (comes with Node.js)
- **PHP**: Version 7.4+ (8.0+ recommended)
- **Composer**: Latest version
- **Git**: Latest version
- **Local WordPress Environment**: Local by Flywheel, MAMP, XAMPP, or Docker

#### Development Setup

1. **Fork the Repository**:
   Visit the [Author Profile Blocks repository](https://github.com/mralaminahamed/author-profile-blocks) and click the "Fork" button in the top-right corner.

2. **Clone Your Fork**:

    ```bash
    git clone https://github.com/YOUR-USERNAME/author-profile-blocks.git
    cd author-profile-blocks
    ```

3. **Install PHP Dependencies**:

    ```bash
    composer install
    ```

4. **Install JavaScript Dependencies**:

    ```bash
    npm install
    ```

5. **Set Up WordPress Environment**:

    ```bash
    # Create a local WordPress installation
    # Option 1: Using WP-CLI
    wp core download
    wp config create --dbname=author_blocks_dev --dbuser=root --dbpass=password
    wp core install --url=http://localhost --title="Author Blocks Dev" --admin_user=admin --admin_email=admin@example.com

    # Option 2: Using Docker
    docker run -d --name wp-dev -p 8080:80 -v $(pwd):/var/www/html/wp-content/plugins/author-profile-blocks wordpress:php8.1-apache
    ```

6. **Start Development Server**:

    ```bash
    npm start
    ```

7. **Build for Production**:
    ```bash
    npm run build
    ```

#### Alternative Development Environments

**Using Docker:**

```bash
# Clone the repository
git clone https://github.com/YOUR-USERNAME/author-profile-blocks.git
cd author-profile-blocks

# Start Docker environment
docker-compose up -d

# Install dependencies
docker-compose exec wordpress composer install
docker-compose exec wordpress npm install

# Start development
docker-compose exec wordpress npm start
```

**Using GitHub Codespaces:**

1. Open the repository in GitHub
2. Click "Code" → "Codespaces" → "Create codespace"
3. The environment will be automatically configured
4. Run `npm start` to begin development

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

### Automated Testing

#### PHP Unit Tests

For PHP unit testing using PHPUnit:

```bash
# Run all PHP tests
composer test

# Run specific test file
composer test -- --filter Author_Profile_Service_Test

# Run tests with coverage
composer test:coverage

# Run tests in watch mode
composer test:watch
```

#### JavaScript Unit Tests

For JavaScript/React testing using Jest:

```bash
# Run all JS tests
npm test

# Run tests in watch mode
npm run test:watch

# Run tests with coverage
npm run test:coverage

# Run tests for specific component
npm test -- --testPathPattern=AuthorPicker
```

#### End-to-End Tests

For end-to-end testing using Playwright:

```bash
# Run all E2E tests
npm run test:e2e

# Run tests in headed mode (visible browser)
npm run test:e2e:headed

# Run tests on specific browser
npm run test:e2e:chromium
npm run test:e2e:firefox
npm run test:e2e:webkit

# Run tests in debug mode
npm run test:e2e:debug

# Generate test report
npm run test:e2e:report
```

### Visual Regression Testing

Using Playwright's visual comparison:

```bash
# Update visual snapshots
npm run test:visual:update

# Run visual regression tests
npm run test:visual
```

### Performance Testing

#### Lighthouse CI

```bash
# Run Lighthouse performance tests
npm run lighthouse

# Test specific URL
npm run lighthouse -- --url=http://localhost:3000
```

#### WebPageTest Integration

```bash
# Run WebPageTest
npm run webpagetest
```

### Manual Testing Checklist

Before submitting a pull request, manually test:

#### Core Functionality

- [ ] All blocks render correctly in the editor
- [ ] Block settings panels work properly
- [ ] Author selection and filtering functions
- [ ] Content display options toggle correctly
- [ ] Styling controls apply changes

#### Frontend Rendering

- [ ] Blocks display correctly on frontend
- [ ] Responsive behavior on mobile/tablet/desktop
- [ ] Theme compatibility across different themes
- [ ] Dark mode support (if applicable)
- [ ] Print styles work correctly

#### User Experience

- [ ] Loading states are handled gracefully
- [ ] Error states display helpful messages
- [ ] Keyboard navigation works
- [ ] Screen reader accessibility
- [ ] Touch interactions on mobile

#### Integration Testing

- [ ] Compatibility with popular page builders
- [ ] ACF, Meta Box, and custom field integration
- [ ] Multilingual plugin compatibility
- [ ] Popular caching plugins
- [ ] CDN integration

#### Performance

- [ ] Page load times are reasonable
- [ ] Large author lists perform well
- [ ] Memory usage is optimized
- [ ] Network requests are minimized

#### Security

- [ ] Input sanitization works
- [ ] Output escaping is proper
- [ ] Nonce verification is implemented
- [ ] SQL injection prevention
- [ ] XSS protection

### Cross-Browser Testing

#### Automated Cross-Browser Testing

Using BrowserStack or Sauce Labs integration:

```bash
# Run tests on multiple browsers
npm run test:cross-browser

# Test specific browser/OS combinations
npm run test:cross-browser -- --browsers="chrome,firefox,safari,edge"
```

#### Manual Cross-Browser Checklist

- [ ] Chrome (latest 2 versions)
- [ ] Firefox (latest 2 versions)
- [ ] Safari (latest 2 versions)
- [ ] Edge (latest 2 versions)
- [ ] Mobile Safari (iOS latest)
- [ ] Chrome Mobile (Android latest)

### Accessibility Testing

#### Automated Accessibility Testing

```bash
# Run axe-core accessibility tests
npm run test:a11y

# Generate accessibility report
npm run test:a11y:report
```

#### Manual Accessibility Checklist

- [ ] Keyboard navigation works
- [ ] Screen reader compatibility
- [ ] Color contrast meets WCAG standards
- [ ] Focus indicators are visible
- [ ] ARIA labels are appropriate
- [ ] Semantic HTML structure
- [ ] Alt text for images
- [ ] Form labels and instructions

## Development Workflow & Tools

### Modern Development Tools

#### Code Quality & Linting

```bash
# Run all linting tasks
npm run lint

# PHP linting with PHPCS
composer phpcs

# JavaScript/TypeScript linting
npm run lint:js

# CSS/SCSS linting
npm run lint:css

# Markdown linting
npm run lint:md

# Fix auto-fixable issues
npm run lint:fix
composer phpcs:fix
```

#### Code Formatting

```bash
# Format PHP code
composer format

# Format JavaScript/TypeScript
npm run format:js

# Format all code
npm run format
```

#### Static Analysis

```bash
# PHP static analysis with PHPStan
composer phpstan

# Run Psalm (alternative static analyzer)
composer psalm

# Security analysis
composer security:check
```

### Build System

#### Development Builds

```bash
# Start development server with hot reload
npm start

# Start development server on specific port
npm start -- --port=3001

# Development build with source maps
npm run build:dev
```

#### Production Builds

```bash
# Production build with optimizations
npm run build

# Production build with bundle analysis
npm run build:analyze

# Build for specific environment
npm run build:staging
npm run build:production
```

#### Build Optimization

```bash
# Bundle size analysis
npm run bundle:analyze

# Generate build stats
npm run build:stats

# Optimize images
npm run optimize:images
```

### Packaging & Deployment

#### Creating Releases

```bash
# Create plugin package
npm run package

# Create release with version bump
npm run release

# Create release with changelog
npm run release:changelog
```

#### Deployment Automation

```bash
# Deploy to WordPress.org
npm run deploy:wporg

# Deploy to custom repository
npm run deploy:custom

# Create GitHub release
npm run github:release
```

### Version Management

#### Semantic Versioning

The project follows [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

#### Version Commands

```bash
# Bump version (patch)
npm version patch

# Bump version (minor)
npm version minor

# Bump version (major)
npm version major

# Bump version with custom
npm version 1.2.3
```

### Continuous Integration

#### GitHub Actions Workflows

The project uses GitHub Actions for:

- **CI Pipeline**: Automated testing on push/PR
- **Code Quality**: Linting and static analysis
- **Security**: Automated security scanning
- **Release**: Automated deployment on tag push

#### Local CI Simulation

```bash
# Run full CI pipeline locally
npm run ci

# Run specific CI jobs
npm run ci:test
npm run ci:lint
npm run ci:build
```

### Modern Development Practices

#### Git Workflow

```bash
# Create feature branch
git checkout -b feature/amazing-feature

# Create bugfix branch
git checkout -b bugfix/issue-number

# Create hotfix branch
git checkout -b hotfix/critical-fix

# Interactive rebase for clean commits
git rebase -i HEAD~3

# Squash commits
git reset --soft HEAD~3
git commit -m "Feature: Amazing new feature"
```

#### Commit Conventions

Following Conventional Commits:

```bash
# Feature commits
git commit -m "feat: add new author carousel block"

# Bug fixes
git commit -m "fix: resolve carousel autoplay issue"

# Documentation
git commit -m "docs: update installation guide"

# Style changes
git commit -m "style: format code with prettier"

# Refactoring
git commit -m "refactor: simplify author query logic"

# Performance
git commit -m "perf: optimize image lazy loading"

# Testing
git commit -m "test: add unit tests for author service"

# Build/CI
git commit -m "ci: update github actions workflow"

# Chore
git commit -m "chore: update dependencies"
```

#### Pull Request Process

1. **Create PR**: Use GitHub's "Compare & pull request" feature
2. **PR Template**: Fill out all sections of the PR template
3. **Branch Protection**: Ensure CI passes before requesting review
4. **Code Review**: Address reviewer feedback
5. **Merge**: Use "Squash and merge" for clean history

### Advanced Development Tools

#### Docker Development Environment

```bash
# Start full development environment
docker-compose up -d

# Run tests in container
docker-compose exec wordpress composer test
docker-compose exec wordpress npm test

# Access WordPress admin
open http://localhost:8080/wp-admin
```

#### VS Code Configuration

Recommended VS Code extensions and settings:

```json
{
	"recommendations": [
		"ms-vscode.vscode-typescript-next",
		"esbenp.prettier-vscode",
		"ms-vscode.vscode-json",
		"bmewburn.vscode-intelephense-client",
		"ms-vscode.vscode-php-debug",
		"formulahendry.auto-rename-tag",
		"christian-kohler.path-intellisense"
	],
	"settings": {
		"editor.formatOnSave": true,
		"editor.defaultFormatter": "esbenp.prettier-vscode",
		"php.validate.executablePath": "./vendor/bin/php",
		"typescript.preferences.importModuleSpecifier": "relative"
	}
}
```

#### Pre-commit Hooks

Using Husky for Git hooks:

```bash
# Install Husky
npm run prepare

# Pre-commit hooks run automatically:
# - Lint staged files
# - Run tests
# - Check for secrets
# - Validate commit messages
```

#### Dependency Management

```bash
# Check for outdated dependencies
npm outdated
composer outdated

# Update dependencies
npm update
composer update

# Audit dependencies for security
npm audit
composer audit

# Fix security issues
npm audit fix
composer audit:fix
```

### Performance Monitoring

#### Bundle Analysis

```bash
# Analyze JavaScript bundle size
npm run bundle:analyzer

# Analyze CSS bundle size
npm run css:analyzer
```

#### Lighthouse Performance

```bash
# Run Lighthouse on development site
npm run lighthouse:dev

# Run Lighthouse on production build
npm run lighthouse:prod
```

### Documentation

#### Auto-generated Documentation

```bash
# Generate API documentation
npm run docs:api

# Generate component documentation
npm run docs:components

# Build full documentation site
npm run docs:build
```

#### Documentation Testing

```bash
# Check for broken links
npm run docs:links

# Check documentation coverage
npm run docs:coverage

# Validate documentation structure
npm run docs:validate
```

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
