<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Models;

use PHPUnit\Framework\TestCase;

/**
 * Property Test: PHPStan compliance for all generated FHIR model files
 *
 * This test validates that all generated FHIR model classes pass PHPStan
 * static analysis at level 8, ensuring type safety and code quality.
 *
 * @author Health Level Seven International (Terminology Infrastructure)
 */
class PHPStanCompliancePropertyTest extends TestCase
{
    private const MODELS_PATH = 'src/Component/Models/src';

    private const SUPPORTED_VERSIONS = ['R4', 'R4B', 'R5'];

    /**
     * Property 5: Code quality standards compliance (PHPStan)
     *
     * Validates: Requirements 7.3
     *
     * This property test ensures that all generated FHIR model files
     * pass PHPStan static analysis without errors, maintaining high
     * code quality and type safety standards.
     */
    public function testAllGeneratedModelsPassPhpStanAnalysis(): void
    {
        $phpstanConfigPath = 'phpstan.neon';
        self::assertFileExists($phpstanConfigPath, 'PHPStan configuration file must exist');

        // Run PHPStan analysis on Models component
        $command = sprintf(
            'php vendor/bin/phpstan.phar analyse --configuration=%s --no-progress --error-format=json src/Component/Models/src/',
            escapeshellarg($phpstanConfigPath),
        );

        $output   = [];
        $exitCode = 0;
        exec($command . ' 2>&1', $output, $exitCode);

        $outputString = implode("\n", $output);

        // Parse JSON output if available
        $errors = [];
        if ($exitCode !== 0) {
            // Try to extract JSON from output
            foreach ($output as $line) {
                if (str_starts_with($line, '{') && str_ends_with($line, '}')) {
                    $jsonData = json_decode($line, true);
                    if ($jsonData && isset($jsonData['files'])) {
                        foreach ($jsonData['files'] as $file => $fileErrors) {
                            foreach ($fileErrors['messages'] as $error) {
                                $errors[] = sprintf(
                                    '%s:%d - %s',
                                    $file,
                                    $error['line']    ?? 0,
                                    $error['message'] ?? 'Unknown error',
                                );
                            }
                        }
                    }
                }
            }
        }

        // If we couldn't parse JSON, fall back to checking exit code
        if (empty($errors) && $exitCode !== 0) {
            // Count error lines in output for reporting
            $errorLines = array_filter($output, function($line) {
                return strpos($line, 'ERROR')    !== false
                       || strpos($line, 'Found') !== false && strpos($line, 'errors') !== false;
            });

            if (!empty($errorLines)) {
                $lastErrorLine = end($errorLines);
                if (preg_match('/Found (\d+) errors?/', $lastErrorLine, $matches)) {
                    $errorCount = (int) $matches[1];
                    self::fail(sprintf(
                        "PHPStan analysis failed with %d errors. This indicates type safety issues in generated FHIR models.\n\nSample output:\n%s",
                        $errorCount,
                        implode("\n", array_slice($output, -20)), // Show last 20 lines
                    ));
                }
            }

            self::fail(sprintf(
                "PHPStan analysis failed (exit code: %d). Generated FHIR models must pass static analysis.\n\nOutput:\n%s",
                $exitCode,
                $outputString,
            ));
        }

        if (!empty($errors)) {
            self::fail(sprintf(
                "PHPStan found %d errors in generated FHIR models:\n\n%s",
                count($errors),
                implode("\n", array_slice($errors, 0, 10)) . (count($errors) > 10 ? "\n... and " . (count($errors) - 10) . ' more errors' : ''),
            ));
        }

        // If we get here, PHPStan passed
        self::assertTrue(true, 'All generated FHIR models pass PHPStan static analysis');
    }

    /**
     * Test that PHPStan configuration includes Models component path
     */
    public function testPhpStanConfigurationIncludesModelsComponent(): void
    {
        $configPath = 'phpstan.neon';
        self::assertFileExists($configPath, 'PHPStan configuration file must exist');

        $configContent = file_get_contents($configPath);
        self::assertNotFalse($configContent, 'Could not read PHPStan configuration');

        self::assertStringContainsString(
            'src/Component/Models/src/',
            $configContent,
            'PHPStan configuration must include Models component path',
        );
    }

    /**
     * Test that all FHIR versions have generated models
     */
    public function testAllSupportedVersionsHaveGeneratedModels(): void
    {
        foreach (self::SUPPORTED_VERSIONS as $version) {
            $versionPath = self::MODELS_PATH . '/' . $version;
            self::assertDirectoryExists(
                $versionPath,
                "Generated models directory must exist for FHIR version $version",
            );

            // Check that the version has some PHP files
            $phpFiles = $this->getPhpFilesInDirectory($versionPath);
            self::assertGreaterThan(
                0,
                count($phpFiles),
                "FHIR version $version must have generated PHP model files",
            );
        }
    }

    /**
     * Test that generated models have proper namespace structure
     */
    public function testGeneratedModelsHaveProperNamespaceStructure(): void
    {
        foreach (self::SUPPORTED_VERSIONS as $version) {
            $versionPath = self::MODELS_PATH . '/' . $version;
            if (!is_dir($versionPath)) {
                continue;
            }

            $phpFiles = $this->getPhpFilesInDirectory($versionPath);

            // Sample a few files to check namespace structure
            $sampleFiles = array_slice($phpFiles, 0, min(10, count($phpFiles)));

            foreach ($sampleFiles as $file) {
                $content = file_get_contents($file);
                self::assertNotFalse($content, "Could not read file: $file");

                // Check for proper namespace declaration
                self::assertMatchesRegularExpression(
                    '/namespace\s+Ardenexal\\\\FHIRTools\\\\Component\\\\Models\\\\' . $version . '\\\\/',
                    $content,
                    "File $file must have proper Models component namespace for version $version",
                );

                // Check for strict types declaration
                self::assertStringContainsString(
                    'declare(strict_types=1);',
                    $content,
                    "File $file must have strict types declaration",
                );
            }
        }
    }

    /**
     * Get all PHP files in a directory recursively
     */
    private function getPhpFilesInDirectory(string $directory): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        $phpFiles = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $phpFiles[] = $file->getPathname();
            }
        }

        return $phpFiles;
    }
}
