# Author Profile Blocks - Test Suite

This directory contains comprehensive tests for the Author Profile Blocks plugin, including both PHP unit tests and Playwright E2E tests.

## Directory Structure

```
tests/
├── php/                    # PHPUnit tests
│   ├── bootstrap.php       # Test bootstrap
│   ├── src/               # Test classes organized by component
│   │   ├── Admin/         # Admin-related tests
│   │   ├── Blocks/        # Block-specific tests
│   │   ├── Services/      # Service layer tests
│   │   ├── Core/          # Core functionality tests
│   │   ├── CustomAssertion/ # Custom test assertions
│   │   ├── Factories/     # Test data factories
│   │   └── Helpers/       # Test helper utilities
│   └── AuthorProfileBlocksTestCase.php # Base test case
└── pw/                    # Playwright E2E tests
    ├── tests/             # Test specifications
    ├── pages/             # Page object models
    ├── utils/             # Test utilities and helpers
    ├── test-results/      # Test execution results
    ├── test-artifacts/    # Screenshots, videos, traces
    ├── playwright.config.ts # Playwright configuration
    ├── tsconfig.json      # TypeScript configuration
    ├── package.json       # Node.js dependencies
    └── .env.example       # Environment variables template
```

## PHP Unit Tests

### Setup

1. Ensure PHPUnit is installed via Composer
2. Set up a WordPress test environment
3. Run tests with: `composer test`

### Test Structure

- **AuthorProfileBlocksTestCase**: Base test case with common functionality
- **Custom Assertions**: Specialized assertions for plugin-specific testing
- **Factories**: Generate test data (users, posts, etc.)
- **Helpers**: Utility functions for test setup and teardown

### Key Test Areas

- **Admin Tests**: Admin interface functionality, settings, user management
- **Block Tests**: Gutenberg block registration, rendering, configuration
- **Service Tests**: Author data handling, REST API endpoints, caching
- **Core Tests**: Meta providers, registerables, base functionality

## Playwright E2E Tests

### Setup

1. Install dependencies: `cd tests/pw && npm install`
2. Copy environment file: `cp .env.example .env` and configure
3. Install browsers: `npx playwright install`
4. Run tests: `npm test`

### Configuration

- **playwright.config.ts**: Main configuration with projects, timeouts, reporters
- **Page Objects**: Reusable page models for WordPress admin and frontend
- **Custom Matchers**: Specialized assertions for block verification
- **Test Data**: Predefined test users, content, and configurations

### Test Projects

- **local_site_setup**: Local WordPress environment setup
- **site_setup**: General site configuration
- **auth_setup**: User authentication setup
- **e2e_setup**: End-to-end test preparation
- **e2e_tests**: Main E2E test suite

## Running Tests

### PHP Tests

```bash
# Run all PHP tests
composer test

# Run with coverage
composer test:coverage

# Run specific test class
composer test -- --filter=AdminTest

# Run static analysis
composer phpcs:check
composer phpstan
```

### E2E Tests

```bash
cd tests/pw

# Run all tests
npm test

# Run in headed mode (visible browser)
npm run test:headed

# Run specific test
npx playwright test author-blocks.spec.ts

# Run with debugging
npm run test:debug

# View test report
npm run report
```

## Test Data

### PHP Tests
- Uses WordPress test factories for users, posts, terms
- Custom factories for plugin-specific data (authors with profiles)
- Mockery for external dependencies
- Brain Monkey for WordPress function mocking

### E2E Tests
- Predefined test users with different roles
- Sample content and author profiles
- Test selectors for consistent element targeting
- Environment-specific configuration

## Best Practices

### PHP Tests
- Extend `AuthorProfileBlocksTestCase` for common functionality
- Use data providers for parameterized tests
- Mock external dependencies appropriately
- Test both positive and negative scenarios
- Clean up test data in tearDown methods

### E2E Tests
- Use page objects for reusable functionality
- Leverage custom matchers for block-specific assertions
- Test responsive design across viewports
- Verify accessibility features
- Use realistic test data

## CI/CD Integration

Tests are designed to run in CI environments:

- **GitHub Actions**: Automated testing on push/PR
- **Parallel Execution**: Tests run in parallel for faster execution
- **Artifact Collection**: Screenshots and videos for failed tests
- **Coverage Reports**: Code coverage reporting for PHP tests

## Contributing

When adding new tests:

1. Follow existing naming conventions
2. Add appropriate test data and fixtures
3. Include both positive and negative test cases
4. Update documentation as needed
5. Ensure tests pass in CI environment

## Troubleshooting

### Common Issues

**PHP Tests**
- Ensure WordPress test environment is properly configured
- Check that all dependencies are installed
- Verify database connections for integration tests

**E2E Tests**
- Confirm WordPress site is accessible at BASE_URL
- Check admin credentials in .env file
- Ensure browser binaries are installed
- Verify plugin is activated on test site

### Debug Mode

For debugging E2E tests:
- Use `npm run test:debug` for interactive debugging
- Set `HEADLESS=false` to see browser actions
- Add `await page.pause()` in test code for breakpoints
- Check test artifacts in `test-artifacts/` directory