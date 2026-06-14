---
description: Conventional commit conventions used in this repository.
icon: code-commit
---

# Commit Standards

This project uses [Conventional Commits](https://www.conventionalcommits.org/). Roughly 80% of
sampled history follows this style, so new commits must too.

## Format

```
<type>(<scope>): <subject>

[optional body]

[optional footer]
```

* Use `type(scope): subject` or `type: subject`.
* **Scope** is optional and names the affected component, e.g. `serialization`, `codegen`,
  `fhirpath`, `validation`.

## Types

| Type | When to use |
|------|-------------|
| `feat` | New feature or user-visible capability |
| `fix` | Bug fix |
| `chore` | Build scripts, dependencies, tooling, CI |
| `test` | Adding or updating tests |
| `docs` | Documentation only |
| `refactor` | Code restructure with no behaviour change |

## Rules

* **Subject line:** imperative mood ("add support for X", not "added"), no trailing period.
  Keep it concrete — name the behavior, file family, or command that changed. Observed subject
  length is short (p95 around 96 characters); aim for `≤ 72`.
* **Body:** explain *why*, not *what*; wrap at 72 characters. Add a body when the subject covers
  more than one axis or the motivation is not obvious.
* **No AI mentions:** do not reference AI tools, Claude, or AI assistance in commit messages or
  PR descriptions.
* **GPG-sign** commits when possible (`git commit -S`).
* **No `--no-verify`:** pre-commit hooks must pass; fix the underlying issue instead of
  bypassing them.

## Examples

```
feat(serialization): add strict validation mode to JSON deserializer

fix(fhirpath): handle empty collection in where() function

chore: upgrade phpstan to 2.x

test(codegen): add coverage for profile inheritance resolution
```
