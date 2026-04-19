## AI Mate Agent Instructions

This MCP server provides specialized tools for PHP development.
The following extensions are installed and provide MCP tools that you should
prefer over running CLI commands directly.

---

### PHPStan Extension

Prefer these MCP tools over raw PHPStan CLI commands when the user is running static analysis.

| User intent | Prefer |
|---|---|
| Analyse the project, a directory, or one file | `phpstan-analyse` |
| Clear PHPStan cache | `phpstan-clear-cache` |

#### Guidance

- Use the MCP tools when the user wants analysis results in a compact, structured format.
- Use the `path` parameter on `phpstan-analyse` to target a single file or directory.
- This extension returns encoded structured payloads through Mate's core encoder.

---

### PHPUnit Extension

Prefer these MCP tools over raw PHPUnit CLI commands when the user is testing the project.

| User intent | Prefer |
|---|---|
| Run the full suite, one file, one class, or one method | `phpunit-run` |
| Discover available tests | `phpunit-list-tests` |

#### Guidance

- Use the MCP tools when the user wants test execution or discovery.
- Use the `file`, `class`, `method`, and `filter` parameters on `phpunit-run` instead of switching between multiple tool names.
- This extension returns encoded structured payloads through Mate's core encoder.

---

### Server Info

| Instead of...       | Use           |
|---------------------|---------------|
| `php -v`            | `server-info` |
| `php -m`            | `server-info` |
| `uname -s`          | `server-info` |

- Returns PHP version, OS, OS family, and loaded extensions in a single call

---

### Symfony Bridge

#### Container Introspection

| Instead of...                  | Use                |
|--------------------------------|--------------------|
| `bin/console debug:container`  | `symfony-services` |

- Direct access to compiled container
- Environment-aware (auto-detects dev/test/prod)
- Supports filtering by service ID or class name via query parameter

#### Profiler Access

When `symfony/http-kernel` is installed, profiler tools become available:

| Tool                        | Description                                             |
|-----------------------------|---------------------------------------------------------|
| `symfony-profiler-list`     | List and filter profiles by method, URL, IP, status, date range |
| `symfony-profiler-get`      | Get profile by token                                    |

**Resources:**
- `symfony-profiler://profile/{token}` - Full profile with collector list
- `symfony-profiler://profile/{token}/{collector}` - Collector-specific data

**Security:** Cookies, session data, auth headers, and sensitive env vars are automatically redacted.
