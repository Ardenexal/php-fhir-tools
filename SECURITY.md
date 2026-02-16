# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| latest  | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability in this project, please report it responsibly. **Do not open a public GitHub issue for security vulnerabilities.**

### How to Report

1. **Email:** Send a detailed report to **security@ardenexal.com**
2. **GitHub:** Use [GitHub's private vulnerability reporting](https://github.com/Ardenexal/php-fhir-tools/security/advisories/new) if available

### What to Include

- A description of the vulnerability
- Steps to reproduce the issue
- The potential impact
- Any suggested fixes (optional)

### Response Timeline

- **Acknowledgment:** Within 48 hours of receiving your report
- **Initial assessment:** Within 5 business days
- **Resolution target:** Within 30 days for critical issues, 90 days for others

### What to Expect

- We will acknowledge receipt of your report promptly
- We will investigate and validate the issue
- We will work on a fix and coordinate disclosure with you
- We will credit you in the security advisory (unless you prefer anonymity)

## Security Considerations

This project generates PHP code from FHIR Structure Definitions and handles FHIR data serialization. Users should be aware of the following:

- **Code generation:** Generated PHP classes should be reviewed before use in production
- **Package downloads:** FHIR packages are downloaded from external registries; verify package integrity
- **XML handling:** When deserializing FHIR XML from untrusted sources, ensure XXE protections are enabled
- **File paths:** CLI commands that accept file paths should only be used with trusted input

## Scope

The following are in scope for security reports:

- Vulnerabilities in the PHP source code
- Insecure defaults in configuration
- Dependency vulnerabilities that directly affect this project
- Issues in the code generation pipeline that could produce insecure output

The following are out of scope:

- Vulnerabilities in generated FHIR model classes (report to the FHIR specification maintainers)
- Issues in third-party dependencies (report to the upstream project)
- Social engineering attacks
