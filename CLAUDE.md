# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with this repository.

## Project Overview

**PHP FHIR Tools** is a multi-project toolkit for working with FHIR (Fast Healthcare Interoperability Resources) in PHP applications. It generates PHP model classes and enums from FHIR Structure Definitions using Symfony Console and Nette PhpGenerator, and provides FHIR serialization for JSON/XML formats.

## Architecture

```
src/
├── Bundle/FHIRBundle/              # Symfony Bundle integration
├── Component/
│   ├── CodeGeneration/src/         # FHIR class generation
│   ├── Serialization/src/          # FHIR JSON/XML serialization
│   ├── Models/src/                 # Generated FHIR models
│   └── FHIRPath/src/               # FHIRPath expression evaluator
├── Exception/                      # Project-wide exceptions
└── Serialization/                  # Legacy serialization (use Component)
```

**Namespace pattern:** `Ardenexal\FHIRTools\Component\{ComponentName}\`

## Development Commands

```bash
# Install dependencies
composer install

# Generate FHIR models (R4, R4B, R5)
composer run generate-models-all

# Generate specific version
composer run generate-models-r4
composer run generate-models-r4b
composer run generate-models-r5

# Run all tests
composer test

# Run specific test suites
composer test-unit
composer test-integration
composer test-fhir
composer test-fhirpath-spec     # FHIRPath specification conformance tests

# Component-specific tests
composer test:bundle
composer test:codegen
composer test:serialization

# Code quality
composer lint              # Fix code style with Pint
composer phpstan           # Static analysis (level 8)

# Full quality check
composer quality:all       # lint + phpstan + test
```

## AI-Optimized Test Running

**Always use `test-ai` variants** when running tests. They produce compact, token-efficient output by suppressing PHPUnit's verbose format and converting JUnit XML to a one-line summary. A `PreToolUse` hook blocks verbose test commands and shows this hint.

```bash
composer test-ai                         # all suites
composer test-ai-unit                    # unit suite only
composer test-ai-integration             # integration suite
composer test-ai-fhir                    # fhir suite
composer test-ai-fhirpath-spec           # fhirpath conformance tests

composer test-ai -- --filter=FooTest     # with filter (pass args after --)

# Output volume control (--ai-* flags are stripped before passing to PHPUnit)
composer test-ai-fhirpath-spec -- --ai-limit=0              # show all failures
composer test-ai-fhirpath-spec -- --ai-limit=100            # show first 100
composer test-ai-fhirpath-spec -- --ai-limit=100 --ai-offset=100  # page 2
```

**Output format:**
- All pass: `OK 262 tests 0.84s`
- Failures: `FAIL 260/262 tests 1.2s (2F 0E)` followed by `FAIL Class::method | assertion message` per failure
- Default cap: 50 failures shown; trailing line says `... and N more failures (use --ai-offset=X to continue)`

**Always delegate test execution to the `test-runner` subagent** — it uses Haiku (cheaper/faster) and knows the right commands. Example Task tool usage: `subagent_type: "test-runner"`, prompt: `"Run composer test-ai-unit and report results"`.

## AI-Optimized Static Analysis

**Always use `phpstan-ai` variants** for static analysis. They run PHPStan with `--error-format=json --no-progress` and emit a compact one-line-per-error summary. A `PreToolUse` hook blocks verbose `composer phpstan*` commands.

```bash
composer phpstan-ai                      # full codebase
composer phpstan-ai:bundle               # Bundle component only
composer phpstan-ai:codegen              # CodeGeneration component only
composer phpstan-ai:serialization        # Serialization component only
composer phpstan-ai:fhir-path            # FHIRPath component only

# Output volume control (--ai-* flags are stripped before passing to PHPStan)
composer phpstan-ai -- --ai-limit=0                          # show all errors
composer phpstan-ai -- --ai-limit=100                        # show first 100
composer phpstan-ai -- --ai-limit=100 --ai-offset=100        # page 2
```

**Output format:**
- Clean: `OK 0 errors`
- Errors: `ERR N errors in M files` followed by `File.php:line | identifier | message` per error
- Default cap: 50 errors shown; trailing line says `... and N more errors (use --ai-offset=X to continue)`

**Always delegate static analysis to the `phpstan-runner` subagent** — it uses Haiku (cheaper/faster) and knows the right commands. Example Task tool usage: `subagent_type: "phpstan-runner"`, prompt: `"Run composer phpstan-ai:fhir-path and report results"`.

## Key Files

- `demo/bin/console` - Symfony Console entry point (demo app)
- `composer.json` - Dependencies and scripts
- `phpstan.neon` - PHPStan configuration (level 8)
- `config/services.yaml` - Symfony service definitions
- `resources/definitions/` - FHIR definition files

## Coding Standards

- **PHP 8.3+** required
- **Strict types**: Always use `declare(strict_types=1);`
- **PSR-12**: Run `composer lint` to fix code style
- **PHPStan level 8**: Run `composer phpstan` before committing
- **Symfony best practices**: Use dependency injection, avoid `new` in commands
- **PHPUnit 11+/12+**: Use `self::assert*` methods and `void` return types on tests

## Testing Guidelines

- Tests located in `tests/` directory
- Use `Ardenexal\FHIRTools\Tests\` namespace
- Test fixtures in `tests/Fixtures/`
- Integration tests verify FHIR conformance and serialization round-trips

### FHIRPath Specification Tests

The FHIRPath component includes official specification conformance tests in a separate test suite (`fhirpath-spec`). These tests validate implementation against the official FHIR FHIRPath test cases and are currently in development as the evaluator implementation is being completed.

- Run with: `composer test-fhirpath-spec`
- Located in: `src/Component/FHIRPath/tests/Integration/`
- These tests are excluded from the main integration suite to allow focused development
- Track progress toward full FHIRPath 2.0 specification compliance

## FHIR Model Generation

The `fhir:generate` command generates PHP classes from FHIR packages:

```bash
php demo/bin/console fhir:generate --models-component --package=hl7.fhir.r4.core -vvv
```

Generated models go to `src/Component/Models/src/`.

## Serialization

Use the Serialization component for FHIR JSON/XML handling:

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationContext;

$context = FHIRSerializationContext::forJson()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);
```

## Commit Guidelines

- Use conventional commits: `feat:`, `fix:`, `chore:`, `test:`
- No AI mentions in commits or PRs
- Sign commits with GPG when possible
