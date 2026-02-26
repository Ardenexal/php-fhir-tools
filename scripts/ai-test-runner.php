<?php

declare(strict_types=1);

/**
 * AI-optimized PHPUnit test runner.
 *
 * Runs PHPUnit with --no-output --log-junit to suppress verbose output,
 * parses the JUnit XML, and emits a compact token-efficient summary.
 *
 * Usage (direct):
 *   php scripts/ai-test-runner.php [phpunit args...]
 *   php scripts/ai-test-runner.php --testsuite=unit
 *   php scripts/ai-test-runner.php --filter=FooTest
 *
 * Usage (via composer):
 *   composer test-ai
 *   composer test-ai-unit
 *   composer test-ai -- --filter=FooTest
 *
 * AI output control (stripped before passing to PHPUnit):
 *   --ai-limit=N    Max failures to show (default 50; 0 = unlimited)
 *   --ai-offset=N   Skip first N failures (for pagination, default 0)
 *
 * Examples:
 *   composer test-ai-fhirpath-spec -- --ai-limit=0           (show all)
 *   composer test-ai-fhirpath-spec -- --ai-limit=100 --ai-offset=100
 */

// Resolve project root (two levels up from scripts/)
$projectRoot = dirname(__DIR__);
$phpunitBin  = $projectRoot . '/vendor/phpunit/phpunit/phpunit';
$configFile  = $projectRoot . '/phpunit.dist.xml';

if (!file_exists($phpunitBin)) {
    fwrite(STDERR, "PHPUnit not found at: {$phpunitBin}\n");
    exit(1);
}

// Separate our custom --ai-* flags from PHPUnit args
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

// Create a temp file for JUnit XML output
$tmpFile = tempnam(sys_get_temp_dir(), 'phpunit_junit_');
if ($tmpFile === false) {
    fwrite(STDERR, "Failed to create temp file for JUnit output\n");
    exit(1);
}

// Build PHPUnit command parts - inject our output suppression flags
$commandParts = array_merge(
    ['php', $phpunitBin],
    $userArgs,
    [
        '--no-output',
        '--log-junit', $tmpFile,
        '--colors=never',
        '--configuration', $configFile,
    ],
);

$command = implode(' ', array_map('escapeshellarg', $commandParts));

// Execute PHPUnit - capture all pipes to avoid stdout contamination on Windows
$descriptors = [
    0 => ['pipe', 'r'],  // closed immediately (no stdin needed)
    1 => ['pipe', 'w'],  // capture stdout (empty with --no-output)
    2 => ['pipe', 'w'],  // capture stderr; we'll re-emit it after
];

$process = proc_open($command, $descriptors, $pipes, $projectRoot);

if (!is_resource($process)) {
    fwrite(STDERR, "Failed to start PHPUnit process\n");
    @unlink($tmpFile);
    exit(1);
}

fclose($pipes[0]); // child doesn't need stdin

// Drain stdout (empty with --no-output) and capture stderr
stream_get_contents($pipes[1]);
$phpunitStderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

// Re-emit any PHPUnit stderr (boot errors, fatals, deprecations)
if ($phpunitStderr !== '' && $phpunitStderr !== false) {
    fwrite(STDERR, $phpunitStderr);
}

// Parse and output compact results
echo formatResults($tmpFile, $exitCode, $aiLimit, $aiOffset) . "\n";

@unlink($tmpFile);

exit($exitCode);

// ---------------------------------------------------------------------------

function formatResults(string $junitFile, int $exitCode, int $aiLimit = 50, int $aiOffset = 0): string
{
    if (!file_exists($junitFile) || filesize($junitFile) === 0) {
        return $exitCode === 0
            ? 'OK (no test output)'
            : "FAIL (exit code {$exitCode}, no test output)";
    }

    $xml = @simplexml_load_file($junitFile);
    if ($xml === false) {
        return "FAIL (could not parse JUnit XML, exit code {$exitCode})";
    }

    // Aggregate counts directly from testcase elements (avoids double-counting nested suites)
    $testcases = $xml->xpath('//testcase') ?: [];

    $total    = count($testcases);
    $failures = 0;
    $errors   = 0;
    $skipped  = 0;
    $time     = 0.0;

    /** @var list<array{type: string, class: string, method: string, message: string}> $issues */
    $issues = [];

    foreach ($testcases as $tc) {
        $time += (float) ($tc['time'] ?? 0.0);

        // Count and collect failures
        foreach ($tc->failure as $failure) {
            ++$failures;
            // Use element text content (actual assertion message), not 'message' attr (which is the test name)
            $msg = trim((string) $failure);
            $issues[] = buildIssue('FAIL', $tc, $msg !== '' ? $msg : (string) ($failure['message'] ?? ''));
        }

        // Count and collect errors
        foreach ($tc->error as $error) {
            ++$errors;
            $msg = trim((string) $error);
            $issues[] = buildIssue('ERR', $tc, $msg !== '' ? $msg : (string) ($error['message'] ?? ''));
        }

        // Skipped / incomplete
        if (count($tc->skipped) > 0 || isset($tc->{'skipped'})) {
            ++$skipped;
        }
    }

    $timeStr  = number_format($time, 2) . 's';
    $passed   = $total - $failures - $errors - $skipped;
    $issueCount = $failures + $errors;

    // --- All green ---
    if ($issueCount === 0 && $exitCode === 0) {
        $line = "OK {$total} tests {$timeStr}";
        if ($skipped > 0) {
            $line .= " ({$skipped}S)";
        }

        return $line;
    }

    // --- Failures/errors present ---
    $breakdown = "({$failures}F {$errors}E";
    if ($skipped > 0) {
        $breakdown .= " {$skipped}S";
    }
    $breakdown .= ')';

    // Pagination header when offset > 0
    $paginationNote = $aiOffset > 0 ? " [offset={$aiOffset}]" : '';
    $lines = ["FAIL {$passed}/{$total} tests {$timeStr} {$breakdown}{$paginationNote}", '---'];

    $shown   = 0;
    $skipped = 0;
    $effectiveLimit = $aiLimit === 0 ? PHP_INT_MAX : $aiLimit;

    foreach ($issues as $issue) {
        // Skip entries before the offset
        if ($skipped < $aiOffset) {
            ++$skipped;
            continue;
        }

        if ($shown >= $effectiveLimit) {
            break;
        }

        // First line on the header row; continuation lines indented
        $msgLines  = explode("\n", $issue['message']);
        $firstLine = array_shift($msgLines) ?? '';
        $lines[]   = "{$issue['type']} {$issue['class']}::{$issue['method']} | {$firstLine}";

        foreach ($msgLines as $continuation) {
            if (rtrim($continuation) !== '') {
                $lines[] = '  ' . $continuation;
            }
        }

        ++$shown;
    }

    $remaining = count($issues) - $aiOffset - $shown;
    if ($remaining > 0) {
        $lines[] = "... and {$remaining} more failures (use --ai-offset=" . ($aiOffset + $shown) . " to continue)";
    }

    return implode("\n", $lines);
}

/**
 * @return array{type: string, class: string, method: string, message: string}
 */
function buildIssue(string $type, \SimpleXMLElement $tc, string $rawMessage): array
{
    $fullClass = (string) ($tc['classname'] ?? $tc['class'] ?? '');
    $method    = (string) ($tc['name'] ?? '');

    // JUnit XML classname uses dots as separators (not backslashes) - strip namespace prefix
    $dotPos       = strrpos($fullClass, '.');
    $backslashPos = strrpos($fullClass, '\\');
    $lastSep      = max($dotPos === false ? -1 : $dotPos, $backslashPos === false ? -1 : $backslashPos);
    $shortClass   = $lastSep >= 0 ? substr($fullClass, $lastSep + 1) : $fullClass;

    return [
        'type'    => $type,
        'class'   => $shortClass,
        'method'  => $method,
        'message' => cleanMessage($rawMessage),
    ];
}

/**
 * Strip the "FullClass::method" header line PHPUnit emits at the top of JUnit
 * failure text, and return the full remaining message with no truncation.
 */
function cleanMessage(string $text): string
{
    $lines  = explode("\n", trim($text));
    $result = [];

    foreach ($lines as $line) {
        // PHPUnit's JUnit text starts with "FullClass::method" on line 1 - skip it
        if ($result === [] && preg_match('/^[\w\\\\]+::\w+$/', trim($line))) {
            continue;
        }

        $result[] = rtrim($line);
    }

    $cleaned = implode("\n", $result);

    return trim($cleaned) !== '' ? trim($cleaned) : '(no message)';
}
