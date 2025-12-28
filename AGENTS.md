# AGENTS.md
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)  
![Symfony](https://img.shields.io/badge/Symfony-6.4%7C7.4-black)  
![License](https://img.shields.io/badge/license-MIT-green)  
![Status](https://img.shields.io/badge/status-active-success)

---

## Overview
**PHP FHIRTools** is a **multi-project toolkit** for working with **FHIR** in PHP applications. It provides modular components for **code generation**, **serialization**, and **Symfony integration** that can be used independently or together.

---

## Multi-Project Architecture
```text
+------------------+
| FHIRBundle       |  <-- Symfony Bundle Integration
+------------------+
         |
         v
+------------------+    +------------------+
| CodeGeneration   |    | Serialization    |
| Component        |    | Component        |
+------------------+    +------------------+
         |                        |
         v                        v
+------------------+    +------------------+
| FHIR Model       |    | JSON Serializer  |
| Generator        |    | & Validator      |
+------------------+    +------------------+
```

### Components

- **FHIRBundle** (`ardenexal/fhir-bundle`): Symfony Bundle for seamless integration
- **CodeGeneration** (`ardenexal/fhir-code-generation`): Standalone FHIR class generator
- **Serialization** (`ardenexal/fhir-serialization`): Standalone FHIR JSON serialization

---

## Quick Start

### Symfony Integration (Recommended)
```bash
# Install FHIRBundle (includes all components)
composer require ardenexal/fhir-bundle

# Generate FHIR classes
composer run generate-models-all

# Use in your Symfony services
public function __construct(
    private readonly FHIRModelGenerator $generator,
    private readonly FHIRSerializationService $serializer
) {}
```

### Standalone Usage
```bash
# Code generation only
composer require ardenexal/fhir-code-generation

# Serialization only  
composer require ardenexal/fhir-serialization

# Use directly in PHP
$generator = new FHIRModelGenerator($context);
$serializer = new FHIRSerializationService();
```

---

## Essential Commands
### Testing
```bash
composer run test
composer run generate-models-all
```

### Code Quality
```bash
composer run lint      # Fix code style
composer run phpstan   # Static analysis
```

### Component Testing
```bash
# Test specific components
composer run test -- tests/Unit/Bundle/FHIRBundle/
composer run test -- tests/Unit/Component/CodeGeneration/
composer run test -- tests/Unit/Component/Serialization/
```

---

## Multi-Project Structure
- **Repository Organization**: Monorepo with separate component packages
- **Independent Versioning**: Each component can be versioned independently  
- **Modular Usage**: Use only the components you need
- **Backward Compatibility**: Legacy namespaces supported via aliases
- **Cross-Version Support**: Compatible with Symfony 6.4 and 7.4

### Directory Structure
```
src/
├── Bundle/FHIRBundle/           # Symfony Bundle
├── Component/
│   ├── CodeGeneration/          # FHIR class generation
│   └── Serialization/           # FHIR JSON serialization
docs/
├── architecture.md              # Multi-project architecture
├── migration-guide.md           # Migration instructions
└── component-guides/            # Component-specific guides
```

---

## Coding Standards
- **Symfony Best Practices**: Use DI, Console helpers, avoid `new` in commands.
- **Strict Types**: Always `declare(strict_types=1);`.
- **PSR-12 Compliance**: Run PHP-CS-Fixer after changes.
- **Exceptions**: Use project-specific exceptions.
- **Docs**: Add PHPDoc for public methods and `@author` tags.
- **Tests**: PHPUnit 12+, no `void` return types, use `self::assert*`.
- **Multi-Component Development**: Follow component isolation principles.
- **Namespace Migration**: Use new component namespaces for new code.

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
- **Multi-Component Awareness**: Understand component boundaries and dependencies.
- **Backward Compatibility**: Maintain compatibility aliases when moving code.
- **Generated code must**:
    - Be PSR-12 compliant.
    - Include PHPDoc annotations.
    - Use strict types.
    - Follow component namespace conventions.
- **Security**: No sensitive data in logs or comments.
- **Commit Rules**: Conventional commits, no AI mentions.

---

## ✅ AI Agent Checklist
Before submitting changes, confirm:
- [ ] Symfony compliance (DI, Console helpers).
- [ ] `declare(strict_types=1);` in all files.
- [ ] Code passes `composer run lint` and `composer run phpstan`.
- [ ] All PHPUnit tests pass.
- [ ] Component isolation maintained.
- [ ] Backward compatibility preserved.
- [ ] New commands/services documented appropriately.
- [ ] No sensitive data exposed.
- [ ] Conventional commit format used.
- [ ] No breaking changes unless approved.
- [ ] Generated FHIR classes validated.
- [ ] Component-specific tests pass.

---

### Documentation Resources
- **Architecture Guide**: `/docs/architecture.md` - Multi-project structure overview
- **Migration Guide**: `/docs/migration-guide.md` - Step-by-step migration instructions  
- **Component Guides**: `/docs/component-guides/` - Detailed component documentation
- **FHIRBundle Guide**: `/docs/component-guides/fhir-bundle.md` - Symfony integration
- **CodeGeneration Guide**: `/docs/component-guides/code-generation.md` - Standalone generation
- **Serialization Guide**: `/docs/component-guides/serialization.md` - JSON serialization
