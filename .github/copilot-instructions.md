# php-fhir-tools

PHP 8.3+ monorepo toolkit for FHIR: generates model classes from FHIR Structure Definitions, provides
JSON/XML serialization, and evaluates FHIRPath expressions. Core invariant: `src/Component/Models/src/`
is generated output and is only ever changed by regenerating it.

## Truth Order

Code > `.goat-flow/` docs > this file. When sources conflict, trust code first. The Deny tier and
accepted architecture decisions are non-overridable: user approval can release Confirm work, but cannot
authorize secret exposure or bypassing safety enforcement.

## Autonomy Tiers

- **Auto:** edits to `src/`, `tests/`, `composer.json`, `.goat-flow/` docs
- **Confirm:** delete files, push to remote, open or merge PRs, modify CI/CD
- **Deny:** skip pre-commit hooks, bypass PHPStan, commit with `--no-verify`, force push

No hook gates git or `gh` for this agent: `deny-git-mutations` is disabled in `.goat-flow/config.yaml`, and
the permission denies in `.claude/settings.json` do not apply here. These tiers are prose-only for git, so
follow them without a mechanical backstop. `deny-dangerous` still blocks risky shell and secret access.

## Hard Rules

- Never hand-edit `src/Component/Models/src/`; use `fhir:generate`
- Never run `composer phpstan` or `composer test` directly; use the `*-ai` variants
- Always use `declare(strict_types=1)` and PHPStan level 8 on every file
- Any interface or attribute visible to more than one component belongs in `src/Component/Metadata/src/`

## Workspace Boundary

Act only within this repository root. Do not write to parent directories, system paths, or other
projects. Do not conflate the goat-flow workspace with the target project files.

## Commit Messages

When the user asks for a draft commit message, use Conventional `type(scope): subject` - imperative, ≤72 chars, concrete verbs not weak ones (*enhance, improve, update*); one change per subject. No AI mentions in commits or PRs. GPG-sign when possible. Full rules: `.github/git-commit-instructions.md` and `docs/coding-standards/git-commit-message.md`.

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

## Execution Loop: READ → SCOPE → ACT → VERIFY

When a goat-* skill is active, its Step 0 replaces READ and selects the skill's mode/depth. SCOPE still applies before writes: a skill may write when its selected mode permits writes or the user explicitly approves them. `goat-plan` File-Write may create gitignored milestone files without a separate approval gate; `goat-debug` D3 still requires approval before fixes. Resume at ACT after Step 0 output or when a blocking gate releases.

### READ

MUST read relevant files before changes, starting with `.goat-flow/architecture.md` and `.goat-flow/code-map.md`. Never fabricate codebase facts.
Use INDEX-first retrieval across `.goat-flow/learning-loop/{footguns,lessons,patterns}/INDEX.md`; include `.goat-flow/learning-loop/decisions/INDEX.md` for architecture, policy, or setup. Open source entries only on candidate hits; reword once on zero hits, then record the miss.
Before declaring any tool or capability unavailable, read the matching playbook in `.goat-flow/skill-docs/playbooks/` (e.g. `browser-use.md`, `page-capture.md`) and run that doc's "Availability Check" section verbatim - project-local CLI tools at `~/.local/bin/` are valid; do not conflate "no harness/MCP tool" with "no tool".
Before creating, changing, reviewing, consolidating, moving, or pruning tests, read `.goat-flow/skill-docs/playbooks/test-selection.md`.
Prose surfaces route the same way before writing: `CHANGELOG.md` needs `changelog.md`; release notes need `release-notes.md`; ordinary README prose, `docs/`, PR/issue text, and learning-loop entry bodies need `writing-human-facing-prose.md`; skills, playbooks, instruction files, and hook messages need `writing-agent-facing-instructions.md`.

### SCOPE

Identify files to touch, tests to run, and a binary exit criterion. Declare non-goals and max blast radius. Confirm with the user if scope extends beyond `src/` or `tests/` into config, CI, or infra files. Expanding beyond scope = stop and re-scope with the user.

### ACT

MUST declare: `State: [MODE] | Goal: [one line] | Exit: [condition]`

| Mode | Behaviour |
|------|-----------|
| Plan | Produce planning artefacts. `goat-plan` File-Write may create gitignored milestone files when selected; committed files still require explicit approval. Exit on LGTM |
| Implement | Edit in bounded batches. Run `composer test-ai` after each logical unit |
| Explain | Walkthrough only. No changes unless asked |
| Debug | Diagnosis with file + semantic anchor first. Fixes after human reviews |
| Review | Investigate first. Never blindly apply suggestions |

For milestone work, load `goat-plan`. If a milestone changes source, run `goat-clarity` once before exit on the explicit paths that milestone wrote.

### VERIFY

`composer phpstan-ai` clean, `composer test-ai` passes, `composer lint` passes. MUST run `shellcheck` on .sh changes. MUST check cross-references after renames. No hand-edits to generated model files.

**Hallucination red-flags:**
1. **Checks passed.** Do not claim tests pass or any check passed without showing the literal pass/fail line from this session's run. Paraphrase, cached output, or prior-session results do not count.
2. **Completion.** Do not claim completion without listing the specific files changed in this turn. If no files were changed, say so explicitly.
3. **Fix verification.** Do not claim a fix works without running the reproduction steps that originally demonstrated the bug. "Looks correct" is not verification.
4. **Hedged claims.** Do not use "should work", "probably fine", "looks good" as verification. These are guesses, not evidence.

- **Stop-the-line:** When tests break, builds fail, or behaviour regresses - stop expanding scope, preserve evidence, return to diagnosis. Two corrections on the same approach = MUST rewind.
- Recovery: missing context → read first. Out-of-scope → name boundary, redirect. Conflicting sources → flag, ask.

If VERIFY caught a failure or you corrected course, update the learning loop before done, after reading the target directory's `README.md`.

## Definition of Done

All pass: `composer test-ai`, `composer phpstan-ai`, `composer lint`. FHIR models regenerated if StructureDefinitions changed. No hand-edits to generated files.

## Artifact Routing

- New code → `src/Component/<Name>/src/`; new tests → `src/Component/<Name>/tests/`; fixtures → `tests/Fixtures/`
- FHIR models → regenerate via `php demo/bin/console fhir:generate` (never hand-edit)
- Plans/milestones → `.goat-flow/plans/<active>/`
- Footguns, lessons, decisions, patterns → the matching `.goat-flow/learning-loop/` directory

## Router Table

| Resource | Path |
|----------|------|
| Architecture overview | `.goat-flow/architecture.md` |
| Repository layout | `.goat-flow/code-map.md` |
| Domain terminology | `.goat-flow/glossary.md` |
| Learning loop | `.goat-flow/learning-loop/footguns/`, `.goat-flow/learning-loop/lessons/`, `.goat-flow/learning-loop/patterns/`, `.goat-flow/learning-loop/decisions/` |
| Tool playbooks (README index; tools e.g. browser-use, page-capture; disciplines e.g. changelog, release notes, prose style) | `.goat-flow/skill-docs/playbooks/` - read when a request names one, and BEFORE declaring a tool unavailable |
| Skill-authoring methodology | `.goat-flow/skill-docs/skill-quality-testing/` - load the README, then the topical authoring guide |
| Security policy | `.goat-flow/security-policy.md` |
| Cutting a release, version bump, tag, or `gh release` | `.goat-flow/skill-docs/playbooks/release.md` (read BEFORE any bump; publish is irreversible) |
| Copilot skills/config | `.github/skills/`, `.github/hooks/hooks.json` |
| Peer instructions | `CLAUDE.md`, `AGENTS.md` |
