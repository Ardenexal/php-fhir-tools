# CLAUDE.md

## Truth Order

Code > `.goat-flow/` docs > CLAUDE.md. When sources conflict, trust code first.

## Hard Rules

- Never hand-edit `src/Component/Models/src/` — it is generated output; use `fhir:generate`
- Never run `composer phpstan` or `composer test` directly — use `*-ai` variants only
- Always use `declare(strict_types=1)` and PHPStan level 8 on every file

## Autonomy Tiers

- **Auto:** edits to `src/`, `tests/`, `composer.json`, `.goat-flow/` docs
- **Confirm:** delete files, push to remote, open or merge PRs, modify CI/CD
- **Deny:** skip pre-commit hooks, bypass PHPStan, commit with `--no-verify`

## Workspace Boundary

Act only within this repository root. Do not write to parent directories, system paths,
or other projects. `src/Component/Models/src/` is read-only to agents — regenerate via
`fhir:generate`; do not conflate the goat-flow workspace with the target project files.

## Project Overview

PHP 8.3+ monorepo toolkit for FHIR: generates model classes from FHIR Structure Definitions,
provides JSON/XML serialization, and evaluates FHIRPath expressions. Distributed as a Composer
package (`ardenexal/fhir-tools`). **Namespace:** `Ardenexal\FHIRTools\Component\{ComponentName}\`

Full system context: `.goat-flow/architecture.md` · File layout: `.goat-flow/code-map.md`

## Key Resources

| What | Where |
|------|-------|
| Architecture | `.goat-flow/architecture.md` |
| File layout | `.goat-flow/code-map.md` |
| Terminology | `.goat-flow/glossary.md` |
| Footguns | `.goat-flow/learning-loop/footguns/` |
| Lessons | `.goat-flow/learning-loop/lessons/` |
| Decisions | `.goat-flow/learning-loop/decisions/` |
| Skill playbooks (tools) | `.goat-flow/skill-docs/playbooks/` (README.md index; read BEFORE declaring a tool unavailable) |
| Test/PHPStan commands | `.goat-flow/skill-docs/testing.md` |
| Agent instructions | `mate/AGENT_INSTRUCTIONS.md` |

## Router Table

| What you need | Where to look |
|---|---|
| Architecture overview | `.goat-flow/architecture.md` |
| Repository layout | `.goat-flow/code-map.md` |
| Domain terminology | `.goat-flow/glossary.md` |
| Known traps and footguns | `.goat-flow/learning-loop/footguns/` |
| Lessons from past incidents | `.goat-flow/learning-loop/lessons/` |
| Architecture decisions | `.goat-flow/learning-loop/decisions/` |
| Skill playbooks (tools) | `.goat-flow/skill-docs/playbooks/` (README.md index; read BEFORE declaring a tool unavailable) |

## Essential Commands

```bash
composer install                   # install dependencies
composer quality:all               # lint + phpstan + test (run before pushing)
composer run generate-models-all   # regenerate all FHIR models (R4, R4B, R5)
composer test-ai                   # all tests, compact output
composer test-ai-unit              # unit suite only
composer phpstan-ai                # static analysis, compact output
composer lint                      # fix code style with Pint
```

See `.goat-flow/skill-docs/testing.md` for full flag reference and per-component phpstan-ai variants.

## Coding Standards

PHP 8.3+, strict types, PSR-12, PHPStan level 8, Symfony DI, PHPUnit 11+/12+.
Tests: `src/Component/[Package]/tests/`; fixtures: `tests/Fixtures/`.
Metadata component: any interface or attribute visible to more than one component belongs
in `src/Component/Metadata/src/`, not in the component that first needs it.

## Commit Guidelines

Conventional commits: `feat:`, `fix:`, `chore:`, `test:`. No AI mentions in commits or PRs.
GPG-sign when possible. Full rules: `.github/git-commit-instructions.md`.

## Execution Loop

### READ

Before starting: read `.goat-flow/architecture.md` and `.goat-flow/code-map.md`.
Before declaring any tool or capability unavailable, read the matching playbook in
`.goat-flow/skill-docs/playbooks/` (e.g. `browser-use.md`, `page-capture.md`) and run that
doc's "Availability Check" section verbatim — project-local CLI tools at `~/.local/bin/`
are valid; do not conflate "no harness/MCP tool" with "no tool".

### SCOPE

Identify files to touch, tests to run, and a binary exit criterion. Confirm with user if
scope extends beyond `src/` or `tests/` into config, CI, or infra files.

### ACT

Edit minimal files. Run `composer test-ai` after each logical unit. Never hand-edit
`src/Component/Models/src/`; run `php demo/bin/console fhir:generate` instead.
Prefer MCP tools (`phpunit-run`, `phpstan-analyse`) over raw CLI commands.

### VERIFY

`composer phpstan-ai` clean · `composer test-ai` passes · `composer lint` passes.
No hand-edits to generated model files. All exit criteria met with proof, not recollection.

**Hallucination red-flags:**
1. **Checks passed.** Do not claim tests pass or any check passed without showing the literal pass/fail line from this session's run. Paraphrase, cached output, or prior-session results do not count.
2. **Completion.** Do not claim completion without listing the specific files changed in this turn. If no files were changed, say so explicitly.
3. **Fix verification.** Do not claim a fix works without running the reproduction steps that originally demonstrated the bug. "Looks correct" is not verification.
4. **Hedged claims.** Do not use "should work", "probably fine", "looks good" as verification. These are guesses, not evidence.

Full rationalisations table: `.goat-flow/skill-docs/skill-preamble.md` (search: `Rationalisations to reject`).

## Artifact Routing

- New code → `src/Component/<Name>/src/`
- New tests → `src/Component/<Name>/tests/`
- FHIR models → regenerate via `php demo/bin/console fhir:generate` (never hand-edit)
- Plans/milestones → `.goat-flow/tasks/`
- Serialization context: `FHIRSerializationContext::forJson()->withValidationMode(...)`

## Definition of Done

All pass: `composer test-ai`, `composer phpstan-ai`, `composer lint`. FHIR models
regenerated if StructureDefinitions changed. No hand-edits to generated files.

<!-- BEGIN AI_MATE_INSTRUCTIONS -->
AI Mate Summary:
- Role: MCP-powered, project-aware coding guidance and tools.
- Required action: Read and follow `mate/AGENT_INSTRUCTIONS.md` before taking any action in this project, and prefer MCP tools over raw CLI commands whenever possible.
- Installed extensions: matesofmate/phpstan-extension, matesofmate/phpunit-extension, symfony/ai-mate, symfony/ai-symfony-mate-extension.
<!-- END AI_MATE_INSTRUCTIONS -->
