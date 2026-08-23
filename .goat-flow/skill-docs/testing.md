---
goat-flow-reference-version: "1.10.1"
---

# AI-Optimized Test and Static Analysis Reference

## AI-Optimized Test Running

**Always use `test-ai` variants** when running tests. They produce compact, token-efficient output
by suppressing PHPUnit's verbose format and converting JUnit XML to a one-line summary.
A `PreToolUse` hook blocks verbose test commands and shows this hint.

```bash
composer test-ai                         # all suites
composer test-ai-unit                    # unit suite only
composer test-ai-integration             # integration suite
composer test-ai-fhir                    # fhir suite
composer test-ai-fhirpath-spec           # fhirpath conformance tests

composer test-ai -- --filter=FooTest     # with filter (pass args after --)

# Output volume control (--ai-* flags are stripped before passing to PHPUnit)
composer test-ai-fhirpath-spec -- --ai-limit=0              # show all failures
composer test-ai-fhirpath-spec -- --ai-limit=100            # show first 100
composer test-ai-fhirpath-spec -- --ai-limit=100 --ai-offset=100  # page 2
```

**Output format:**
- All pass: `OK 262 tests 0.84s`
- Failures: `FAIL 260/262 tests 1.2s (2F 0E)` followed by `FAIL Class::method | assertion message` per failure
- Default cap: 50 failures shown; trailing line says `... and N more failures (use --ai-offset=X to continue)`

Prefer the `phpunit-run` MCP tool (from AI Mate) over CLI when available — it returns compact structured output directly.

### FHIRPath Specification Tests

The `fhirpath-spec` suite validates against official FHIRPath 2.0 test cases. It is excluded
from the main integration suite to allow focused development.

- Run with: `composer test-ai-fhirpath-spec`
- Located in: `src/Component/FHIRPath/tests/Integration/`

## AI-Optimized Static Analysis

**Always use `phpstan-ai` variants** for static analysis. They run PHPStan with
`--error-format=json --no-progress` and emit a compact one-line-per-error summary.
A `PreToolUse` hook blocks verbose `composer phpstan*` commands.

```bash
composer phpstan-ai                      # full codebase
composer phpstan-ai:bundle               # Bundle component only
composer phpstan-ai:codegen              # CodeGeneration component only
composer phpstan-ai:serialization        # Serialization component only
composer phpstan-ai:fhir-path            # FHIRPath component only

# Output volume control (--ai-* flags are stripped before passing to PHPStan)
composer phpstan-ai -- --ai-limit=0                          # show all errors
composer phpstan-ai -- --ai-limit=100                        # show first 100
composer phpstan-ai -- --ai-limit=100 --ai-offset=100        # page 2
```

**Output format:**
- Clean: `OK 0 errors`
- Errors: `ERR N errors in M files` followed by `File.php:line | identifier | message` per error
- Default cap: 50 errors shown; trailing line says `... and N more errors (use --ai-offset=X to continue)`

Prefer the `phpstan-analyse` MCP tool (from AI Mate) over CLI when available — it returns compact structured output directly.
