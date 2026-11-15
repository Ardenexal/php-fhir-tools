<?php

declare(strict_types=1);

/*
 * Fails unless every installed Symfony component satisfies the given constraint. Guards the
 * cross-version-test legs: a leg that resolves other versions than it names stays green while
 * testing the wrong thing, because framework-bundle 7.4 itself allows symfony/http-kernel ^8.0.
 *
 * Usage: php .github/scripts/assert-symfony-version.php '7.4.*'
 */

use Composer\Semver\Semver;

require __DIR__ . '/../../vendor/autoload.php';

$constraint = $argv[1] ?? '';
if ($constraint === '') {
    fwrite(STDERR, "usage: assert-symfony-version.php <constraint>\n");
    exit(64);
}

/** @var array{packages: list<array{name: string, version: string, version_normalized: string}>} $installed */
$installed = json_decode((string) file_get_contents(__DIR__ . '/../../vendor/composer/installed.json'), true, flags: JSON_THROW_ON_ERROR);

// Versioned on their own schedules rather than with the components. symfony/flex is absent from
// the list because it is installed globally, and symfony/phpunit-bridge because Flex does not
// restrict it: it is a test tool that no shipped code depends on.
$independentlyVersioned = '#^symfony/(polyfill-|ai-|.*-contracts$|contracts$|phpunit-bridge$|flex$)#';

$checked = 0;
$failed  = 0;
foreach ($installed['packages'] as $package) {
    if (!str_starts_with($package['name'], 'symfony/') || preg_match($independentlyVersioned, $package['name']) === 1) {
        continue;
    }

    ++$checked;
    if (Semver::satisfies($package['version_normalized'], $constraint)) {
        continue;
    }

    ++$failed;
    echo "FAIL {$package['name']} {$package['version']}, expected {$constraint}\n";
}

// Zero checked would otherwise read as a pass if the installed.json format ever changed.
if ($checked === 0) {
    fwrite(STDERR, "no Symfony components found in vendor/composer/installed.json\n");
    exit(1);
}

echo "{$checked} Symfony components checked against {$constraint}, {$failed} outside it\n";
exit($failed === 0 ? 0 : 1);
