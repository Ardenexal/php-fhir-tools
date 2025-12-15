# AGENTS.md
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)  
![Symfony](https://img.shields.io/badge/Symfony-7.3-black)  
![License](https://img.shields.io/badge/license-MIT-green)  
![Status](https://img.shields.io/badge/status-active-success)

---

## Overview
**PHP FHIRTools** is a **CLI tool** for generating **FHIR types, resources, and profiles** from FHIR StructureDefinitions into **PHP 8.2+** compatible code using **Symfony Console**.

---

## Architecture
```text
+-------------------+
| Symfony Console   |  <-- CLI Commands
+-------------------+
         |
         v
+-------------------+
| Generator Service |  <-- Converts FHIR StructureDefinitions
+-------------------+
         |
         v
+-------------------+
| Output Directory  |  <-- Generated PHP FHIR Models
+-------------------+
```

---

## Quick Start
```bash
# Install dependencies
composer install

# List available commands
php bin/console list

# Generate FHIR classes
php bin/console fhir:generate R4B
```

---

## Essential Commands
### Testing
```bash
composer run test
composer run generate-models
```

### Code Quality
```bash
composer run lint      # Fix code style
composer run phpstan   # Static analysis
```

---

## Coding Standards
- **Symfony Best Practices**: Use DI, Console helpers, avoid `new` in commands.
- **Strict Types**: Always `declare(strict_types=1);`.
- **PSR-12 Compliance**: Run PHP-CS-Fixer after changes.
- **Exceptions**: Use project-specific exceptions.
- **Docs**: Add PHPDoc for public methods and `@author` tags.
- **Tests**: PHPUnit 12+, no `void` return types, use `self::assert*`.

---

## Git & Commits
- **No AI mentions** in commits or PRs.
- **Sign commits** with GPG.
- **Conventional Commits**:
    - `feat:` new feature
    - `fix:` bug fix
    - `chore:` maintenance
    - `test:` test changes

---

## AI Agent Behavior Guidelines
- **Do not introduce breaking changes** unless approved.
- **Preserve Symfony best practices**.
- **Never remove type hints or strict typing**.
- **Avoid unnecessary dependencies**.
- **Generated code must**:
    - Be PSR-12 compliant.
    - Include PHPDoc annotations.
    - Use strict types.
- **Security**: No sensitive data in logs or comments.
- **Commit Rules**: Conventional commits, no AI mentions.

---

## ✅ AI Agent Checklist
Before submitting changes, confirm:
- [ ] Symfony compliance (DI, Console helpers).
- [ ] `declare(strict_types=1);` in all files.
- [ ] Code passes `composer run lint` and `composer run phpstan`.
- [ ] All PHPUnit tests pass.
- [ ] New commands/services documented in `README.md`.
- [ ] No sensitive data exposed.
- [ ] Conventional commit format used.
- [ ] No breaking changes unless approved.
- [ ] Generated FHIR classes validated.

---

### Next Steps
- Add badges for **code coverage** & **build status**.
- Include a **Quick Start for AI agents** (step-by-step workflow).
- Generate a **real architecture diagram image** for the CLI flow.
