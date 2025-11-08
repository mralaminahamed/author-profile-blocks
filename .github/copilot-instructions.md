# Copilot Instructions for Author Profile Blocks Plugin

## Purpose
This file provides guidelines for using GitHub Copilot in the Author Profile Blocks plugin project.

## Coding Standards
- Follow WordPress PHP coding standards for all PHP files.
- Use ES6+ syntax for JavaScript/React code in the `src/` directory.
- Use SCSS for styling in Gutenberg blocks.
- Ensure code is linted and formatted before committing.
- Use WordPress native functions and APIs wherever possible.

## Best Practices
- Write clear, self-documenting code and add comments where necessary.
- Use functional components and hooks in React for Gutenberg blocks.
- Use dependency injection and avoid global state in PHP classes.
- Keep functions and components small and focused.
- Avoid duplicating code; use shared utilities/components.
- Sanitize all inputs and escape all outputs.
- Use nonces for AJAX requests.
- Follow WordPress block development best practices.

## File Structure
- `includes/`: PSR-4 autoloaded classes organized by functionality.
- `src/blocks/`: Gutenberg block implementations with block.json files.
- `src/js/`: Shared JavaScript utilities and components.
- `src/scss/`: SCSS stylesheets with variables and mixins.
- `build/`: Compiled assets, do not edit directly.

## Commit Guidelines
- Follow Conventional Commits (see git-commit-instructions.md).
- Reference related issues in commit messages when applicable.

## Testing
- Add or update tests for new features and bug fixes.
- Use PHPUnit for PHP tests.
- Test WordPress integration and Gutenberg block functionality.

## Documentation
- Update README.md for major changes or new features.
- Document public APIs and important functions/classes.
- Update AGENTS.md for architectural changes.

## Copilot Usage
- Use Copilot to suggest code, but always review and test before committing.
- Do not accept Copilot suggestions that violate project standards or introduce security risks.
- Refactor Copilot-generated code to match project conventions if needed.
- Always consider WordPress and Gutenberg integration requirements.

---
For questions, contact a maintainer or refer to project documentation.

