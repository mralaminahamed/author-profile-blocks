# Security Policy

## Supported Versions

We actively support the following versions with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability in Author Profile Blocks, please help us by reporting it responsibly.

### How to Report

- **GitHub**: Create a private security advisory at https://github.com/mralaminahamed/author-profile-blocks/security/advisories/new
- **Do not** create public issues for security vulnerabilities

### What to Include

- A clear description of the vulnerability
- Steps to reproduce the issue
- Potential impact and severity
- Any suggested fixes or mitigations

### Our Process

1. **Acknowledgment**: We'll acknowledge receipt within 48 hours
2. **Investigation**: We'll investigate and validate the report
3. **Fix Development**: We'll develop and test a fix
4. **Disclosure**: We'll coordinate disclosure with you
5. **Release**: We'll release the fix and security advisory

### Guidelines

- Please allow reasonable time for us to investigate and fix issues
- We'll credit you in the security advisory (unless you prefer anonymity)
- We follow responsible disclosure practices
- We won't take legal action against security researchers

## Security Best Practices

### For Users

- Keep WordPress and all plugins updated
- Use strong passwords and enable two-factor authentication
- Regularly backup your site
- Use HTTPS for all connections
- Limit admin access to trusted users only

### For Developers

- All user inputs are sanitized and validated
- AJAX requests use WordPress nonces
- Direct database queries use WordPress APIs
- Sensitive data is never logged or exposed
- Block attributes are properly sanitized

## Security Features

- **Input Sanitization**: All user inputs are properly sanitized and validated
- **Nonce Verification**: AJAX and form submissions use WordPress nonces
- **Capability Checks**: Admin functions check user capabilities
- **Block Security**: Gutenberg block attributes are validated and sanitized
- **User Data Protection**: Author profile data is handled securely
- **Modular Architecture**: Isolated components reduce security surface area
- **Logging**: Sensitive data is never logged or exposed
- **HTTPS Enforcement**: All communications require secure connections

## Contact

For security-related questions or concerns:

- GitHub Security Advisories: https://github.com/mralaminahamed/author-profile-blocks/security/advisories