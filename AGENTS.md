# AGENTS.md
![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue)  
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

All components are distributed as a single package (`ardenexal/fhir-tools`):

- **Metadata**: Shared PHP 8 attributes and contracts used by all other components
- **CodeGeneration**: FHIR class generator from Structure Definitions
- **Models**: Generated FHIR model classes (R4, R4B, R5) — do not hand-edit
- **Serialization**: FHIR JSON/XML serialization and validation
- **FHIRPath**: FHIRPath expression parser and evaluator
- **FHIRBundle**: Symfony Bundle wiring all components together

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
Prefer the `phpunit-run` MCP tool over CLI. It returns compact structured output and
accepts `file`, `class`, `method`, and `filter` parameters.

```bash
# Fallback CLI only — use phpunit-run MCP tool instead when available
composer test-ai        # all tests, compact output
composer test-ai-unit   # unit suite only

composer run generate-models-all   # regenerate all FHIR models (no MCP equivalent)
```

### Code Quality
Prefer the `phpstan-analyse` MCP tool over CLI. Use the `path` parameter to target a
single file or directory.

```bash
# Fallback CLI only — use phpstan-analyse MCP tool instead when available
composer phpstan-ai  # static analysis, compact output

composer lint        # fix code style with Pint (no MCP equivalent)
```

### Component Testing
```bash
# Via MCP: phpunit-run with filter="CodeGeneration" (or Serialization / FHIRPath)
# Via CLI fallback (tests live under src/Component/<Name>/tests/):
composer test-ai -- --filter CodeGeneration
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
│   ├── Metadata/                # Shared attributes and contracts
│   ├── CodeGeneration/          # FHIR class generation
│   ├── Models/                  # Generated FHIR models (do not hand-edit)
│   ├── Serialization/           # FHIR JSON/XML serialization
│   └── FHIRPath/                # FHIRPath expression evaluator
docs/
└── component-guides/            # Component-specific guides
    ├── fhir-bundle.md
    ├── fhir-path.md
    └── serialization.md
```

---

## Coding Standards
- **Symfony Best Practices**: Use DI, Console helpers, avoid `new` in commands.
- **Strict Types**: Always `declare(strict_types=1);`.
- **PSR-12 Compliance**: Run PHP-CS-Fixer after changes.
- **Exceptions**: Use project-specific exceptions.
- **Docs**: Add PHPDoc for public methods.
- **Tests**: PHPUnit 12+, use `void` return types and `self::assert*`.
- **Multi-Component Development**: Follow component isolation principles.
- **Namespace Migration**: Use new component namespaces for new code.

---

## Git & Commits
- **No AI mentions** in commits or PRs.
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
- [ ] `phpstan-analyse` MCP tool (or `composer phpstan-ai`) reports no errors.
- [ ] `phpunit-run` MCP tool (or `composer test-ai`) passes.
- [ ] `composer lint` passes (no MCP equivalent).
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
- **Architecture Guide**: `.goat-flow/architecture.md` - System architecture and component overview
- **Code Map**: `.goat-flow/code-map.md` - File layout and hot paths
- **FHIRBundle Guide**: `docs/component-guides/fhir-bundle.md` - Symfony integration
- **FHIRPath Guide**: `docs/component-guides/fhir-path.md` - FHIRPath evaluator
- **Serialization Guide**: `docs/component-guides/serialization.md` - JSON/XML serialization

## Execution Loop

### READ

Before starting any task, orient with these sources:

- Read `.goat-flow/architecture.md` for system context.
- Read `.goat-flow/code-map.md` for file layout.
- Before declaring any tool or capability unavailable, read the matching playbook in `.goat-flow/skill-reference/` (e.g. `browser-use.md`, `page-capture.md`) and run that doc's "Availability Check" section verbatim — project-local CLI tools at `~/.local/bin/` are valid; do not conflate "no harness/MCP tool" with "no tool".

### SCOPE

Identify files to touch, tests to run, and a binary exit criterion. Confirm with user if scope extends beyond `src/` or `tests/` into config, CI, or infra files.

### ACT

Edit minimal files. Run `phpunit-run` (MCP) after each logical unit; fall back to `composer test-ai` if MCP is unavailable. Never hand-edit `src/Component/Models/src/`; run `php demo/bin/console fhir:generate` instead.

### VERIFY

`phpstan-analyse` (MCP) clean · `phpunit-run` (MCP) passes · `composer lint` passes. No hand-edits to generated model files. All exit criteria met with proof, not recollection.

## Router Table

| What you need | Where to look |
|---|---|
| Architecture overview | `.goat-flow/architecture.md` |
| Repository layout | `.goat-flow/code-map.md` |
| Domain terminology | `.goat-flow/glossary.md` |
| Known traps and footguns | `.goat-flow/footguns/` |
| Lessons from past incidents | `.goat-flow/lessons/` |
| Architecture decisions | `.goat-flow/decisions/` |
| Tool playbooks (CLI/MCP availability checks: browser-use, page-capture, skill-* references) | `.goat-flow/skill-reference/` — read BEFORE declaring a tool unavailable |

---

<!-- BEGIN AI_MATE_INSTRUCTIONS -->
AI Mate Summary:
- Role: MCP-powered, project-aware coding guidance and tools.
- Required action: Read and follow `mate/AGENT_INSTRUCTIONS.md` before taking any action in this project, and prefer MCP tools over raw CLI commands whenever possible.
- Installed extensions: matesofmate/phpstan-extension, matesofmate/phpunit-extension, symfony/ai-mate, symfony/ai-symfony-mate-extension.
<!-- END AI_MATE_INSTRUCTIONS -->
