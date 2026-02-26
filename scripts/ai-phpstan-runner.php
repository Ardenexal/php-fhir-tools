<?php

declare(strict_types=1);

/**
 * AI-optimized PHPStan static analysis runner.
 *
 * Runs PHPStan with --error-format=json --no-progress to suppress verbose output,
 * parses the JSON, and emits a compact token-efficient summary.
 *
 * Usage (direct):
 *   php scripts/ai-phpstan-runner.php [phpstan args...]
 *   php scripts/ai-phpstan-runner.php src/Component/FHIRPath/
 *
 * Usage (via composer):
 *   composer phpstan-ai
 *   composer phpstan-ai:fhir-path
 *
 * AI output control (stripped before passing to PHPStan):
 *   --ai-limit=N    Max errors to show (default 50; 0 = unlimited)
 *   --ai-offset=N   Skip first N errors (for pagination, default 0)
 *
 * Examples:
 *   composer phpstan-ai -- --ai-limit=0                        (show all)
 *   composer phpstan-ai -- --ai-limit=100 --ai-offset=100
 */

// Resolve project root (two levels up from scripts/)
$projectRoot = dirname(__DIR__);
$phpstanBin  = $projectRoot . '/vendor/bin/phpstan.phar';

if (!file_exists($phpstanBin)) {
    fwrite(STDERR, "PHPStan not found at: {$phpstanBin}\n");
    exit(1);
}

// Separate our custom --ai-* flags from PHPStan args
$aiLimit  = 50;   // default: cap at 50
$aiOffset = 0;    // default: start from beginning
$userArgs = [];

foreach (array_slice($argv, 1) as $arg) {
    if (preg_match('/^--ai-limit=(\d+)$/', $arg, $m)) {
        $aiLimit = (int) $m[1];
    } elseif (preg_match('/^--ai-offset=(\d+)$/', $arg, $m)) {
        $aiOffset = (int) $m[1];
    } else {
        $userArgs[] = $arg;
    }
}

// Build PHPStan command - inject JSON format and suppress progress
$commandParts = array_merge(
    ['php', $phpstanBin, 'analyse'],
    $userArgs,
    [
        '--error-format=json',
        '--no-progress',
        '--configuration=' . $projectRoot . '/phpstan.neon',
        '--memory-limit=-1',
    ],
);

$command = implode(' ', array_map('escapeshellarg', $commandParts));

// Execute PHPStan - capture stdout (JSON), let stderr pass through
$descriptors = [
    0 => ['pipe', 'r'],  // stdin (closed immediately)
    1 => ['pipe', 'w'],  // stdout: JSON output
    2 => ['pipe', 'w'],  // stderr: boot errors, warnings
];

$process = proc_open($command, $descriptors, $pipes, $projectRoot);

if (!is_resource($process)) {
    fwrite(STDERR, "Failed to start PHPStan process\n");
    exit(1);
}

fclose($pipes[0]); // child doesn't need stdin

$jsonOutput    = stream_get_contents($pipes[1]);
$phpstanStderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

// Re-emit PHPStan stderr (config errors, boot warnings)
if ($phpstanStderr !== '' && $phpstanStderr !== false) {
    fwrite(STDERR, $phpstanStderr);
}

// Parse and output compact results
if ($jsonOutput === false || $jsonOutput === '') {
    echo ($exitCode === 0 ? 'OK 0 errors' : "ERR (no JSON output, exit code {$exitCode})") . "\n";
    exit($exitCode);
}

echo formatResults($jsonOutput, $exitCode, $projectRoot, $aiLimit, $aiOffset) . "\n";

exit($exitCode);

// ---------------------------------------------------------------------------

function formatResults(string $jsonOutput, int $exitCode, string $projectRoot, int $aiLimit = 50, int $aiOffset = 0): string
{
    $data = json_decode($jsonOutput, true);

    if (!is_array($data)) {
        return $exitCode === 0
            ? 'OK 0 errors'
            : "ERR (could not parse PHPStan JSON, exit code {$exitCode})";
    }

    /** @var array{totals?: array{errors?: int, file_errors?: int}, files?: array<string, array{errors?: int, messages?: list<array{message: string, line: int, identifier?: string}>}>, errors?: list<string>} $data */

    $globalErrors = (array) ($data['errors'] ?? []);
    $files        = (array) ($data['files'] ?? []);
    // totals.errors = global (non-file) errors; totals.file_errors = per-file errors
    $globalErrorCount = (int) ($data['totals']['errors'] ?? 0);
    $fileErrors       = (int) ($data['totals']['file_errors'] ?? 0);
    $totalErrors      = $globalErrorCount + $fileErrors;

    // All clean
    if ($totalErrors === 0 && $exitCode === 0) {
        return 'OK 0 errors';
    }

    $fileCount      = count(array_filter($files, fn ($f) => (int) ($f['errors'] ?? 0) > 0));
    $paginationNote = $aiOffset > 0 ? " [offset={$aiOffset}]" : '';
    $summary        = "ERR {$totalErrors} errors in {$fileCount} files{$paginationNote}";

    /** @var list<string> $lines */
    $lines          = [$summary, '---'];
    $shown          = 0;
    $skipped        = 0;
    $effectiveLimit = $aiLimit === 0 ? PHP_INT_MAX : $aiLimit;

    // Flatten all errors into a single sequence: globals first, then per-file
    // This makes offset/limit work uniformly across both error types.

    // Global errors first (config issues, missing files, etc.)
    foreach ($globalErrors as $msg) {
        if ($skipped < $aiOffset) {
            ++$skipped;
            continue;
        }
        if ($shown >= $effectiveLimit) {
            break;
        }
        $lines[] = 'GLOBAL | ' . truncate((string) $msg);
        ++$shown;
    }

    // Per-file errors
    $projectRootNorm = rtrim(str_replace('\\', '/', $projectRoot), '/');

    foreach ($files as $filePath => $fileData) {
        if ($shown >= $effectiveLimit) {
            break;
        }

        $messages = (array) ($fileData['messages'] ?? []);
        $relPath  = relativePath((string) $filePath, $projectRootNorm);

        foreach ($messages as $msg) {
            if ($skipped < $aiOffset) {
                ++$skipped;
                continue;
            }
            if ($shown >= $effectiveLimit) {
                break;
            }

            /** @var array{message: string, line: int, identifier?: string} $msg */
            $line       = (int) ($msg['line'] ?? 0);
            $identifier = (string) ($msg['identifier'] ?? '');
            $message    = (string) ($msg['message'] ?? '');

            $identPart = $identifier !== '' ? " | {$identifier}" : '';
            $lines[]   = "{$relPath}:{$line}{$identPart} | " . truncate($message);
            ++$shown;
        }
    }

    $remaining = $totalErrors - $aiOffset - $shown;
    if ($remaining > 0) {
        $lines[] = "... and {$remaining} more errors (use --ai-offset=" . ($aiOffset + $shown) . " to continue)";
    }

    return implode("\n", $lines);
}

function relativePath(string $absPath, string $projectRoot): string
{
    $normPath = str_replace('\\', '/', $absPath);

    if (str_starts_with($normPath, $projectRoot . '/')) {
        return substr($normPath, strlen($projectRoot) + 1);
    }

    // Return just the filename as fallback
    return basename($normPath);
}

function truncate(string $text): string
{
    return trim($text);
}
