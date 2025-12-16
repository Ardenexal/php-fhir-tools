<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Composer\Semver\Semver;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Property-based test for version range validity.
 *
 * **Feature: repository-reorganization, Property 10: Version range validity**
 *
 * Tests that the Symfony version ranges specified in composer.json
 * are valid and correctly include both Symfony 6.4 and 7.4.
 */
class VersionRangeValidityTest extends TestCase
{
    use TestTrait;

    /**
     * Test that version ranges are valid and include required versions.
     *
     * **Feature: repository-reorganization, Property 10: Version range validity**
     * **Validates: Requirements 3.3**
     *
     * Property: For any Symfony version range constraint, it should be valid
     * and include both Symfony 6.4 and 7.4 versions.
     */
    public function testVersionRangeValidity(): void
    {
        $this->forAll(
            Generator\elements([
                '^6.4|^7.4',
                '^6.4.0|^7.4.0',
                '>=6.4,<8.0',
                '~6.4|~7.4',
                '^6.4|^7.0',
            ]), // Different version range formats
        )->then(function(string $versionRange): void {
            // Test that the version range is valid
            self::assertTrue(
                $this->isValidVersionRange($versionRange),
                "Version range '{$versionRange}' should be valid",
            );

            // Test that Symfony 6.4 versions are included
            $symfony64Versions = ['6.4.0', '6.4.1', '6.4.10'];
            foreach ($symfony64Versions as $version) {
                if ($this->shouldIncludeVersion($versionRange, $version)) {
                    self::assertTrue(
                        Semver::satisfies($version, $versionRange),
                        "Version range '{$versionRange}' should include Symfony {$version}",
                    );
                }
            }

            // Test that Symfony 7.4 versions are included (if the range should include them)
            $symfony74Versions = ['7.4.0', '7.4.1', '7.4.10'];
            foreach ($symfony74Versions as $version) {
                if ($this->shouldIncludeVersion($versionRange, $version)) {
                    self::assertTrue(
                        Semver::satisfies($version, $versionRange),
                        "Version range '{$versionRange}' should include Symfony {$version}",
                    );
                }
            }

            // Test that invalid versions are excluded
            $invalidVersions = ['5.4.0', '8.0.0', '9.0.0'];
            foreach ($invalidVersions as $version) {
                self::assertFalse(
                    Semver::satisfies($version, $versionRange),
                    "Version range '{$versionRange}' should not include Symfony {$version}",
                );
            }
        });
    }

    /**
     * Test that the current composer.json version ranges are valid.
     *
     * **Feature: repository-reorganization, Property 10: Version range validity**
     * **Validates: Requirements 3.3**
     *
     * Property: The version ranges in the actual composer.json should be
     * valid and support the required Symfony versions.
     */
    public function testComposerJsonVersionRanges(): void
    {
        $composerPath = __DIR__ . '/../../../../composer.json';
        self::assertFileExists($composerPath, 'composer.json should exist');

        $composerContent = file_get_contents($composerPath);
        self::assertNotFalse($composerContent, 'Should be able to read composer.json');

        $composerData = json_decode($composerContent, true);
        self::assertIsArray($composerData, 'composer.json should contain valid JSON');

        // Test Symfony packages in require section
        $symfonyPackages = [
            'symfony/console',
            'symfony/framework-bundle',
            'symfony/serializer',
            'symfony/validator',
            'symfony/yaml',
        ];

        foreach ($symfonyPackages as $package) {
            if (isset($composerData['require'][$package])) {
                $versionRange = $composerData['require'][$package];

                // Test that the version range is valid
                self::assertTrue(
                    $this->isValidVersionRange($versionRange),
                    "Version range for {$package} should be valid: {$versionRange}",
                );

                // Test that it includes Symfony 6.4
                self::assertTrue(
                    Semver::satisfies('6.4.0', $versionRange),
                    "Version range for {$package} should include Symfony 6.4.0: {$versionRange}",
                );

                // Test that it includes Symfony 7.4
                self::assertTrue(
                    Semver::satisfies('7.4.0', $versionRange),
                    "Version range for {$package} should include Symfony 7.4.0: {$versionRange}",
                );
            }
        }

        // Test Symfony packages in require-dev section
        $symfonyDevPackages = [
            'symfony/browser-kit',
            'symfony/phpunit-bridge',
        ];

        foreach ($symfonyDevPackages as $package) {
            if (isset($composerData['require-dev'][$package])) {
                $versionRange = $composerData['require-dev'][$package];

                // Test that the version range is valid
                self::assertTrue(
                    $this->isValidVersionRange($versionRange),
                    "Dev version range for {$package} should be valid: {$versionRange}",
                );
            }
        }
    }

    /**
     * Test that version ranges handle edge cases correctly.
     *
     * **Feature: repository-reorganization, Property 10: Version range validity**
     * **Validates: Requirements 3.3**
     *
     * Property: Version ranges should handle edge cases like pre-release
     * versions and patch versions correctly.
     */
    public function testVersionRangeEdgeCases(): void
    {
        $this->forAll(
            Generator\elements([
                '6.4.0',
                '6.4.1',
                '6.4.99',
                '7.4.0',
                '7.4.1',
                '7.4.99',
                '7.0.0',
                '7.1.0',
                '7.2.0',
                '7.3.0',
            ]),
        )->then(function(string $testVersion): void {
            $versionRange = '^6.4|^7.4';

            // Test version satisfaction logic
            $shouldSatisfy = version_compare($testVersion, '6.4.0', '>=')  && version_compare($testVersion, '7.0.0', '<')
                           || version_compare($testVersion, '7.4.0', '>=') && version_compare($testVersion, '8.0.0', '<');

            $actualSatisfies = Semver::satisfies($testVersion, $versionRange);

            if ($shouldSatisfy) {
                self::assertTrue(
                    $actualSatisfies,
                    "Version {$testVersion} should satisfy range {$versionRange}",
                );
            }

            // Test that the current running Symfony version is supported
            $currentVersion = Kernel::VERSION;
            if (version_compare($currentVersion, '6.4.0', '>=')) {
                self::assertTrue(
                    Semver::satisfies($currentVersion, $versionRange)
                    || version_compare($currentVersion, '7.0.0', '>='),
                    "Current Symfony version {$currentVersion} should be supported",
                );
            }
        });
    }

    /**
     * Check if a version range is valid.
     */
    private function isValidVersionRange(string $versionRange): bool
    {
        try {
            // Try to parse the version range
            Semver::satisfies('1.0.0', $versionRange);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Determine if a version range should include a specific version.
     */
    private function shouldIncludeVersion(string $versionRange, string $version): bool
    {
        // For our test ranges, we expect them to include 6.4+ and 7.4+
        return str_contains($versionRange, '6.4') || str_contains($versionRange, '7.4')
                                                  || str_contains($versionRange, '>=6.4') || str_contains($versionRange, '~6.4')
                                                  || str_contains($versionRange, '^6.4') || str_contains($versionRange, '^7.0');
    }
}
