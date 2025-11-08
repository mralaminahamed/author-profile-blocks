# AGENTS.md - Author Profile Blocks

## Reference Plugin: Warranty Cart

Reference the `warranty-cart` plugin (/Users/alamin/Sites/woocommerce/wp-content/plugins/warranty-cart) for:

- Modern WordPress plugin architecture with PSR-4 autoloading
- TypeScript + React admin interface with Shadcn UI components
- Comprehensive testing (PHPUnit, Playwright E2E)
- WordPress native functions usage (`wp_enqueue_script`, `wp_localize_script`, etc.)
- Proper namespace structure (`WarrantyCart\`)
- Security best practices (nonces, sanitization, escaping)
- Modern build tools and dependency management

## Project Structure

### Key Directories

- `includes/`: PSR-4 autoloaded PHP classes
    - `Admin/`: Admin interface classes (Admin, Plugin_Links)
    - `Blocks/`: Gutenberg block classes (Author_Profile_Block, Author_Grid_Block, etc.)
    - `Core/`: Core functionality (Meta_Data_Provider, User_Meta_Provider, Registerable)
    - `Services/`: Service layer classes (Author_Profile_Service)
    - `Supports/`: Third-party integrations (FakerPress support)
- `src/blocks/`: Gutenberg block implementations with block.json files
- `src/js/`: Shared JavaScript utilities and React components
- `src/scss/`: SCSS stylesheets with variables and mixins
- `src/types/`: TypeScript type definitions
- `build/`: Compiled assets (do not edit directly)
- `docs/`: Documentation and guides
- `languages/`: Translation files (.po, .pot, .mo)
- `tests/`: Test files (PHP and E2E)
- `.github/`: GitHub workflows, templates, and configuration

### Important Files

- `author-profile-blocks.php`: Main plugin file with headers
- `class-author-profile-blocks.php`: Main plugin class with initialization
- `includes/Supports/FakerPress.php`: FakerPress integration support
- `includes/Supports/`: WordPress Supports API integration (future-ready)
- `composer.json`: PHP dependencies and scripts
- `package.json`: JavaScript dependencies and build scripts
- `webpack.config.js`: Webpack configuration with dependency mapping
- `AGENTS.md`: This guidelines file for AI agents

## Build/Lint/Test Commands

### Frontend (JavaScript/React)

- `yarn start` - Development build with watch mode
- `yarn build` - Production build
- `yarn lint` - Run all linting (JS, CSS, markdown, package.json)
- `yarn plugin-zip` - Create plugin distribution zip
- `yarn bundle-analyze` - Analyze webpack bundle
- `yarn type` - TypeScript type checking (if using TS)

### Backend (PHP)

- `composer phpcs:check` - Check PHP code standards
- `composer phpcs:fix` - Fix PHP code standards violations
- `composer phpcs:check:review` - Check with WordPress plugin review standards
- `composer phpstan` - Run static analysis
- `composer wp:make-pot` - Generate translation pot file
- `composer test` - Run PHPUnit tests (if configured)

### Release

- `composer release` - Complete release process (build, lint, analyze, package)

### GitHub Workflows

- CI: Automated testing on push/PR (PHP 8.1-8.3, WP latest/6.0+)
- Deploy: Automated WordPress.org deployment on tag push
- Security: Automated security scanning with Trivy
- Dependencies: Automated dependency updates via Dependabot

## Code Style Guidelines

### PHP

- Follow WordPress coding standards with PSR-4 autoloading (reference: warranty-cart)
- Namespace: `AuthorProfileBlocks\` (includes/) or `APBL\` (legacy)
- Supports Directory: `AuthorProfileBlocks\Supports\` for third-party integrations
- Prefixes: `author_profile_blocks`, `apbl`, `APBL`
- PHP 7.4+ minimum, tabs for indentation, strict typing
- Use type declarations and return types where possible
- Dependency injection, avoid global state
- Security: Validate/sanitize input, escape output, nonce verification
- Use WordPress native functions: `wp_enqueue_script`, `wp_localize_script`, `wp_ajax_*`
- Follow warranty-cart patterns for class structure and method naming

### JavaScript/React

- ES6+ syntax, WordPress ESLint config
- Single quotes, semicolons always, tabs for indentation
- Functional components with hooks preferred
- WordPress components (`@wordpress/components`) for Gutenberg blocks
- Import order: WordPress deps first, then internal
- JSDoc comments for component props and functions
- Path alias: `@/*` for `src/*` directory
- Consider TypeScript for complex admin interfaces (like warranty-cart)
- Use Shadcn UI + Tailwind for modern admin UIs (reference: warranty-cart)

### CSS/SCSS

- WordPress stylelint config with recess order
- SCSS with mixins and variables in `src/scss/common/`
- Follow BEM-like naming conventions
- Responsive design with mobile-first approach
- Consider Tailwind CSS for admin interfaces (reference: warranty-cart)

### General

- UTF-8 encoding, LF line endings, final newlines
- Trim trailing whitespace, tabs for PHP/JS indentation
- Clear self-documenting code, avoid unnecessary comments
- Small focused functions/components, avoid code duplication
- Block registration uses `block.json` files
- Full internationalization support with text domain `author-profile-blocks`
- Follow warranty-cart patterns for file structure and organization
- Use WordPress core functions and APIs wherever possible

## GitHub Management

### Issue Templates

- **Bug Report**: Comprehensive bug reporting with environment details
- **Feature Request**: Structured feature requests with use cases
- **Block Enhancement**: Block-specific improvements and modifications
- **Documentation**: Documentation issues and improvements
- **Security Issue**: Secure vulnerability reporting (private advisories)
- **Question & Support**: General help and troubleshooting

### Labels & Organization

- `bug`: Bug reports and fixes
- `enhancement`: Feature requests and improvements
- `feature-request`: New feature suggestions
- `documentation`: Documentation updates
- `security`: Security-related issues
- `question`: Support questions
- `block-*`: Block-specific issues (profile, grid, list, carousel)

### Commit Guidelines

- Follow Conventional Commits: `type(scope): description`
- Types: feat, fix, docs, style, refactor, perf, test, chore
- Scopes: block, admin, frontend, core, supports, carousel, grid, fakerpress, etc.
- Always run linting and tests before committing

## Block Development Patterns

### Block Structure

- Each block has its own directory in `src/blocks/`
- Required files: `block.json`, `index.js`, `edit.js`, `style.scss`
- Optional: `view.js` (for frontend-only functionality), `editor.scss`, `components/`
- Use `block.json` for block registration and settings

### Block Registration

- Register blocks in PHP using `register_block_type()`
- Use `block.json` for metadata (title, icon, category, attributes)
- Define attributes with proper validation and defaults
- Support internationalization with `__()` functions

### Component Patterns

- Use functional components with React hooks
- Follow WordPress component library (`@wordpress/components`)
- Implement proper prop types and JSDoc comments
- Handle accessibility with proper ARIA attributes

### Styling Guidelines

- Use SCSS with BEM-like naming conventions
- Define variables in `src/scss/common/_variables.scss`
- Create mixins in `src/scss/common/_mixins.scss`
- Ensure responsive design with mobile-first approach
- Use CSS custom properties for dynamic styling

## Third-Party Integrations

### FakerPress Support

- **Location**: `includes/Supports/FakerPress.php`
- **Purpose**: Automatic user generation with Author Profile Blocks metadata
- **Features**:
    - Registers custom meta fields with FakerPress
    - Generates realistic author descriptions, positions, and social profiles
    - Hooks into both general and specific FakerPress filters
    - Provides default values for new users
- **Integration Points**:
    - `fakerpress/fields/meta_types` - Register meta field types
    - `fakerpress.module.user.before_save` - Set default values
    - `fakerpress.module.meta.value` - General meta value generation
    - `fakerpress.module.meta.{key}.value` - Specific meta key generation

### WordPress Supports API (Future)

- **Location**: `includes/Supports/` directory
- **Purpose**: Future WordPress Supports API integration
- **Current Status**: Prepared for when WordPress implements the Supports API
- **Features**: AI-powered plugin capabilities and automated actions

## Testing & Quality Assurance

### PHP Testing

- Write PHPUnit tests for classes in `tests/php/`
- Test both positive and negative scenarios
- Mock WordPress functions when necessary
- Run `composer test` before committing

### JavaScript Testing

- Use React Testing Library for component tests
- Test user interactions and accessibility
- Mock WordPress API calls appropriately
- Run `yarn test` if test scripts are available

### Code Quality Checks

- **PHP**: `composer phpcs:check` (WordPress standards)
- **PHP**: `composer phpstan` (static analysis)
- **JS/CSS**: `yarn lint` (ESLint, Stylelint)
- **Security**: Manual review for input sanitization and output escaping

### Pre-commit Checklist

1. Run all linting: `composer phpcs:check && yarn lint`
2. Run static analysis: `composer phpstan`
3. Test functionality in WordPress environment
4. Check for security vulnerabilities
5. Update documentation if needed
6. Test with different user roles and themes

## Deployment & Release

### Version Management

- Update version in `author-profile-blocks.php` header
- Update version in `composer.json` and `package.json`
- Update `CHANGELOG.md` with release notes
- Tag releases with semantic versioning (e.g., `v1.0.0`)

### Build Process

- Run `yarn build` to compile assets
- Run `composer install --no-dev --optimize-autoloader` for production
- Generate translation files: `composer wp:make-pot`
- Create plugin zip: `yarn plugin-zip`

### WordPress.org Deployment

- Automated via GitHub Actions on tag push
- Validates version alignment between files
- Deploys to WordPress.org plugin repository
- Creates GitHub release with download link

### Release Checklist

1. Update version numbers across all files
2. Update changelog with detailed release notes
3. Test in staging environment
4. Run full CI pipeline
5. Create git tag and push
6. Monitor deployment and address any issues
7. Announce release in relevant channels
