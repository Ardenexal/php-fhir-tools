# Contributing to PHP FHIR Tools

Thank you for your interest in contributing! This document provides guidelines for contributing to the project.

## Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Extensions: `ctype`, `iconv`, `zip`

### Setup

```bash
git clone https://github.com/Ardenexal/php-fhir-tools.git
cd php-fhir-tools
composer install
```

### Verify Your Setup

```bash
composer quality:all
```

This runs code style fixes, static analysis, and the full test suite.

## Development Workflow

1. **Fork** the repository and create a branch from `main`
2. **Write code** following the coding standards below
3. **Add tests** for any new functionality
4. **Run the quality checks** before submitting
5. **Submit a pull request** against `main`

## Coding Standards

- **PHP 8.3+** — use modern PHP features
- **Strict types** — every file must declare `declare(strict_types=1);`
- **PSR-12** — code style is enforced by Laravel Pint
- **PHPStan level 8** — all code must pass static analysis

### Running Quality Checks

```bash
# Fix code style
composer lint

# Run static analysis
composer phpstan

# Run all tests
composer test

# Run specific test suites
composer test-unit
composer test-integration

# Run everything (lint + phpstan + tests)
composer quality:all
```

### Component-Specific Checks

```bash
# Run quality checks for a single component
composer quality:bundle
composer quality:codegen
composer quality:serialization
composer quality:fhir-path
```

## Project Structure

```
src/
├── Bundle/FHIRBundle/              # Symfony Bundle integration
├── Component/
│   ├── CodeGeneration/src/         # FHIR class generation
│   ├── Serialization/src/          # FHIR JSON/XML serialization
│   ├── Models/src/                 # Generated FHIR models
│   └── FHIRPath/src/               # FHIRPath expression evaluator
├── Exception/                      # Project-wide exceptions
└── Serialization/                  # Legacy serialization
```

**Namespace pattern:** `Ardenexal\FHIRTools\Component\{ComponentName}\`

## Testing

- Tests go in the `tests/` directory using the `Ardenexal\FHIRTools\Tests\` namespace
- Use PHPUnit 11+/12+ conventions (`self::assert*`, `void` return types)
- Place test fixtures in `tests/Fixtures/`
- Integration tests should verify FHIR conformance and serialization round-trips
- **All new code must have tests** — aim for meaningful coverage, not just line coverage

## Commit Messages

Use [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` — new feature
- `fix:` — bug fix
- `test:` — adding or updating tests
- `chore:` — maintenance, dependencies, tooling
- `docs:` — documentation changes
- `refactor:` — code restructuring without behavior change

Examples:
```
feat: add FHIRPath union operator support
fix: resolve XML namespace handling in Bundle resources
test: add round-trip tests for Observation serialization
```

## Pull Requests

- Keep PRs focused — one feature or fix per PR
- Include a clear description of what changed and why
- Ensure all CI checks pass before requesting review
- Update documentation if your change affects the public API

## Reporting Bugs

- Use [GitHub Issues](https://github.com/Ardenexal/php-fhir-tools/issues)
- Include PHP version, Symfony version, and steps to reproduce
- Include the FHIR version (R4, R4B, R5) if relevant

## Security Vulnerabilities

**Do not open public issues for security vulnerabilities.** See [SECURITY.md](SECURITY.md) for reporting instructions.

## Environment Variables

- Use `.env.local` for local development overrides (this file is gitignored)
- Never commit secrets, passwords, API keys, or tokens to any file
- See `.env` for the list of available configuration variables

## License

By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE).
