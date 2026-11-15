---
goat-flow-reference-version: "1.10.1"
---
# AI Mate MCP Tools

Use this playbook when running PHP tests, static analysis, inspecting server info, or interrogating the Symfony service container. AI Mate MCP tools are preferred over raw CLI commands — they return structured, compact output through Mate's core encoder.

## Availability Check

Before using any AI Mate MCP tool, confirm the MCP server is live by calling `server-info`. A successful response returns the PHP version, OS family, and loaded extensions. If the call errors or the tool is absent, MCP is unavailable — fall back to the CLI commands in `## Fallback CLI Commands`.

```
Call: server-info
Expected: PHP version string + OS family + loaded extensions list
Failure: tool not found, timeout, or error response → MCP unavailable
```

## Tool Preference Rules

| User intent | Prefer (MCP) | Instead of |
|---|---|---|
| Run the full test suite, one file, one class, or one method | `phpunit-run` | `composer test-ai`, `vendor/bin/phpunit` |
| Discover available tests | `phpunit-list-tests` | `vendor/bin/phpunit --list-tests` |
| Analyse the project, a directory, or one file | `phpstan-analyse` | `composer phpstan-ai`, `vendor/bin/phpstan` |
| Clear PHPStan cache | `phpstan-clear-cache` | `vendor/bin/phpstan clear-result-cache` |
| Check PHP version or loaded extensions | `server-info` | `php -v`, `php -m` |
| Inspect the Symfony service container | `symfony-services` | `bin/console debug:container` |
| List Symfony profiler profiles | `symfony-profiler-list` | `bin/console debug:router` / manual curl |
| Retrieve a specific Symfony profile | `symfony-profiler-get` | manual profiler URL navigation |

### Parameter guidance

- **`phpunit-run`:** Use the `file`, `class`, `method`, and `filter` parameters to target specific tests. Do not invoke separate tool names per targeting granularity.
- **`phpstan-analyse`:** Use the `path` parameter to target a single file or directory.
- **`symfony-services`:** Supports filtering by service ID or class name via the query parameter; environment is auto-detected (dev/test/prod).

## Fallback CLI Commands

Use these only when the MCP server is unavailable (see `## When MCP Is Unavailable`).

| MCP tool | CLI fallback |
|---|---|
| `phpunit-run` (full suite) | `composer test-ai` |
| `phpunit-run` (unit suite only) | `composer test-ai-unit` |
| `phpstan-analyse` | `composer phpstan-ai` |
| `server-info` | `php -v` and `php -m` |
| `symfony-services` | `bin/console debug:container` |

**Hard rule:** Never use `composer test` or `composer phpstan` directly. Always use the `-ai` variants (`composer test-ai`, `composer phpstan-ai`). Raw `vendor/bin/phpunit` invocations discover no tests in this project.

See `CLAUDE.md` Essential Commands for the full list of valid CLI commands.

## When MCP Is Unavailable

If `server-info` errors or the tool is not found:

1. Log the incident tag `ai-mate-mcp-unavailable` in your session notes or output so it is traceable.
2. Switch to the CLI fallbacks listed in `## Fallback CLI Commands`.
3. Do not declare the capability unavailable — the CLI fallbacks provide equivalent outcomes for all core intents (test, static analysis, server info, container inspection).
4. Do not use `vendor/bin/phpunit` or `composer phpstan` directly. Use the `-ai` composer scripts from CLAUDE.md Essential Commands.

## Related References

- `CLAUDE.md` — Essential Commands and Hard Rules sections
- `mate/AGENT_INSTRUCTIONS.md` — canonical source of truth for AI Mate MCP tool preference rules and parameter guidance
- `.goat-flow/skill-docs/testing.md` — full flag reference and per-component `phpstan-ai` variants
