# Git Commit Instructions for Author Profile Blocks Plugin

## Commit Message Format

Please use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) for all commit messages. This helps maintain clarity and consistency in the project history.

**Format:**
```
type(scope): short description

[optional body]
[optional footer]
```

### Types
- feat: A new feature for the plugin
- fix: A bug fix
- docs: Documentation only changes
- style: Changes that do not affect the meaning of the code (white-space, formatting, etc)
- refactor: Code change that neither fixes a bug nor adds a feature
- perf: Performance improvement
- test: Adding or correcting tests
- chore: Maintenance tasks (build, dependencies, etc)

### Scope
Use the relevant area of the plugin, e.g. `block`, `admin`, `frontend`, `core`, `carousel`, `grid`, etc.

**Examples:**
- feat(block): add author profile block with custom fields
- fix(carousel): correct responsive layout on mobile devices
- refactor(core): implement abstract block base class
- docs: update AGENTS.md with block architecture

## Best Practices
- Make small, focused commits.
- Reference related issues in the footer (e.g. `Closes #123`).
- Test your changes before committing.
- Do not commit build files unless necessary.
- Run linting and tests before committing.

## Workflow
1. Pull the latest changes before starting work.
2. Create a new branch for your feature or fix.
3. Make your changes and commit using the format above.
4. Push your branch and open a pull request.

## Before Committing
1. Run `composer phpcs` - Fix any linting issues
2. Run `composer phpstan` - Address static analysis warnings
3. Run `yarn lint` - Ensure JavaScript/CSS code quality
4. Build assets: `yarn build`
5. Test in WordPress environment

---
For questions, see README.md or contact a maintainer.

