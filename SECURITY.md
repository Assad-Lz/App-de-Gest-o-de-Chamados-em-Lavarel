# Security Policy

## Supported Versions

Only the latest version of this study project is supported for security updates.

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

As a project focused on security hardening, we take vulnerabilities seriously.

If you find any security flaw or a way to bypass our current Hardening (Middleware, Obfuscation, CSP), please report it by:

1. Opening a **GitHub Issue** (if it's for educational discussion).
2. Contacting the maintainer directly if it's a critical flaw that could affect others using this for study.

### Our Security Hardening Includes:
- **Backend Obfuscation Middleware**: Hiding the API from common scanners.
- **XssProtectionMiddleware**: Sanitizing all inputs.
- **SecurityHeadersMiddleware**: Strong CSP and security headers.
- **Honeypots**: Active traps for bots.

We will do our best to address issues within 48 hours for learning purposes.
