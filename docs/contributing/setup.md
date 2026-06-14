---
description: Set up the monorepo for local development.
icon: laptop-code
---

# Development Setup

## Prerequisites

* **PHP 8.3** or higher
* **Composer**
* PHP extensions: `bcmath`, `ctype`, `iconv`, `zip`

## Clone and install

```bash
git clone https://github.com/Ardenexal/php-fhir-tools.git
cd php-fhir-tools
composer install
```

## Verify your setup

```bash
composer quality:all   # lint + phpstan + test
```

`quality:all` chains three steps defined in `composer.json`: `@lint` (Laravel Pint code-style
fixes), `@phpstan` (PHPStan level 8 static analysis), and `@test` (the full PHPUnit suite).

## Quality commands

The individual scripts that `quality:all` composes:

| Command | What it does |
|---------|--------------|
| `composer lint` | Fix code style with Laravel Pint (PSR-12) |
| `composer phpstan` | Static analysis at PHPStan level 8 |
| `composer test` | Run the full PHPUnit suite |

### Per-component quality checks

Each component has scoped variants, for example:

```bash
composer quality:bundle
composer quality:codegen
composer quality:serialization
composer quality:fhir-path
composer quality:validation
```

{% hint style="info" %}
**`-ai` variants.** This repo also defines AI-friendly runners with compact output:
`composer phpstan-ai`, `composer test-ai`, and scoped forms such as `composer test-ai-unit`
and `composer phpstan-ai:serialization`. Per repository policy, contributors working through an
agent harness use these `-ai` variants; the plain `phpstan`/`test` scripts produce the same
analysis with verbose output. Note there is no `lint-ai` — use `composer lint` for code style.
{% endhint %}

{% hint style="warning" %}
Generated model files under `src/Component/Models/src/` must never be hand-edited — regenerate
via `php demo/bin/console fhir:generate` (or `composer run generate-models-all`).
{% endhint %}

See [testing.md](testing.md) for the test layout and utilities, and
[commit-standards.md](commit-standards.md) for commit conventions.
