# Commit Conventions

## Format

```
<type>(<scope>): <subject>

[optional body]

[optional footer]
```

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

- **Subject line:** ≤ 72 characters, imperative mood ("add support for X", not "added"), no trailing period
- **Scope:** optional, names the component affected (e.g. `serialization`, `codegen`, `fhirpath`)
- **Body:** explain *why*, not *what*; wrap at 72 characters
- **No AI mentions:** do not reference AI tools, Claude, or AI assistance in commit messages or PR descriptions
- **GPG-sign** commits when possible (`git commit -S`)
- **No `--no-verify`** — pre-commit hooks must pass; fix the underlying issue instead

## Examples

```
feat(serialization): add strict validation mode to JSON deserializer

fix(fhirpath): handle empty collection in where() function

chore: upgrade phpstan to 2.x

test(codegen): add coverage for profile inheritance resolution
```
