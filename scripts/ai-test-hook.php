<?php

declare(strict_types=1);

/**
 * Claude Code PreToolUse hook: intercepts verbose test and static analysis commands.
 *
 * Reads tool input JSON from stdin and blocks commands that would produce
 * verbose PHPUnit or PHPStan output. Redirects to compact AI variants instead.
 *
 * Exit codes:
 *   0 = allow the command through
 *   2 = block the command (reason written to stderr)
 *
 * Blocked patterns:
 *   - composer test (but NOT composer test-ai*)
 *   - composer test-unit, test-integration, test-fhir, test-fhirpath-spec
 *   - composer test:bundle, test:codegen, test:serialization, test:fhir-path
 *   - composer quality:* (internally runs @test and @phpstan)
 *   - phpunit invocations without --no-output
 *   - composer phpstan, phpstan:* (but NOT composer phpstan-ai*)
 *   - direct phpstan/phpstan.phar invocations without --error-format=json
 *
 * Allowed through:
 *   - composer test-ai, test-ai-* (already compact)
 *   - php scripts/ai-test-runner.php (already compact)
 *   - phpunit with --no-output (already suppressed)
 *   - composer phpstan-ai, phpstan-ai:* (already compact)
 *   - php scripts/ai-phpstan-runner.php (already compact)
 *   - phpstan with --error-format=json (already structured)
 *   - Everything else (non-test/non-analysis commands)
 */

$stdin = stream_get_contents(STDIN);

if ($stdin === false || $stdin === '') {
    exit(0);
}

$input = json_decode($stdin, true);
if (!is_array($input)) {
    exit(0);
}

$command = (string) ($input['tool_input']['command'] ?? '');

if ($command === '') {
    exit(0);
}

// Allow list - check first before considering blocks
if (shouldAllow($command)) {
    exit(0);
}

// Block list
$blockReason = shouldBlock($command);
if ($blockReason !== null) {
    fwrite(STDERR, $blockReason);
    exit(2);
}

exit(0);

// ---------------------------------------------------------------------------

function shouldAllow(string $command): bool
{
    // Already using compact test runner - always allow
    if (str_contains($command, 'test-ai') || str_contains($command, 'ai-test-runner')) {
        return true;
    }

    // phpunit with --no-output already suppressed - allow
    if (str_contains($command, 'phpunit') && str_contains($command, '--no-output')) {
        return true;
    }

    // Already using compact phpstan runner - always allow
    if (str_contains($command, 'phpstan-ai') || str_contains($command, 'ai-phpstan-runner')) {
        return true;
    }

    // phpstan with --error-format=json already structured - allow
    if (preg_match('/\bphpstan\b/', $command) && str_contains($command, '--error-format=json')) {
        return true;
    }

    return false;
}

function shouldBlock(string $command): ?string
{
    $testHint = "Use compact test commands instead:\n"
        . "  composer test-ai                     (all tests)\n"
        . "  composer test-ai-unit                (unit suite)\n"
        . "  composer test-ai-integration         (integration suite)\n"
        . "  composer test-ai-fhir                (fhir suite)\n"
        . "  composer test-ai-fhirpath-spec       (fhirpath-spec suite)\n"
        . "  composer test-ai -- --filter=FooTest (with filter)\n";

    $phpstanHint = "Use compact phpstan commands instead:\n"
        . "  composer phpstan-ai                  (full codebase)\n"
        . "  composer phpstan-ai:bundle           (Bundle component)\n"
        . "  composer phpstan-ai:codegen          (CodeGeneration component)\n"
        . "  composer phpstan-ai:serialization    (Serialization component)\n"
        . "  composer phpstan-ai:fhir-path        (FHIRPath component)\n";

    // Block: composer test (but not test-ai, test-coverage, test-recipe, test-package-installation)
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?test\b(?![-_](ai|coverage|recipe|package))/', $command)) {
        return "Blocked verbose test command. {$testHint}";
    }

    // Block: composer test-unit, test-integration, test-fhir, test-fhirpath-spec
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?(test-unit|test-integration|test-fhir|test-fhirpath-spec)\b/', $command)) {
        return "Blocked verbose test command. {$testHint}";
    }

    // Block: composer test:bundle, test:codegen, test:serialization, test:fhir-path, test:components
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?test:[a-z-]+/', $command)) {
        return "Blocked verbose test command. {$testHint}";
    }

    // Block: composer quality:* (these call @test and @phpstan internally)
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?quality:/', $command)) {
        return "Blocked verbose quality command (contains @test and @phpstan). Use phpstan-ai and test-ai variants instead.";
    }

    // Block: direct phpunit invocations without --no-output
    if (preg_match('/\bphpunit\b/', $command) && !str_contains($command, '--no-output')) {
        return "Blocked verbose phpunit invocation. {$testHint}";
    }

    // Block: composer phpstan, phpstan:* (verbose - not phpstan-ai which is already allowed above)
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?phpstan\b(?![-_]ai)/', $command)) {
        return "Blocked verbose phpstan command. {$phpstanHint}";
    }

    // Block: composer phpstan:bundle, phpstan:codegen, etc. (verbose variants)
    if (preg_match('/\bcomposer\s+(run(-script)?\s+)?phpstan:[a-z-]+/', $command)) {
        return "Blocked verbose phpstan command. {$phpstanHint}";
    }

    // Block: direct phpstan/phpstan.phar invocations without --error-format=json
    if (preg_match('/\bphpstan(?:\.phar)?\b/', $command) && !str_contains($command, '--error-format=json')) {
        return "Blocked verbose phpstan invocation. {$phpstanHint}";
    }

    return null;
}
