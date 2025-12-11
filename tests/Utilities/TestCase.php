<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Utilities;

use Ardenexal\FHIRTools\ErrorCollector;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Base test case with common utilities for FHIR Tools testing
 *
 * This class provides common testing utilities and patterns used across
 * the FHIR Tools test suite, including command testing helpers, assertion
 * utilities, and test data management.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Create a command tester for testing Symfony console commands
     */
    protected function createCommandTester(Command $command): CommandTester
    {
        $application = new Application();
        $application->add($command);

        return new CommandTester($command);
    }

    /**
     * Assert that an ErrorCollector contains specific error patterns
     */
    protected function assertErrorCollectorContains(
        ErrorCollector $errorCollector,
        string $messagePattern,
        ?string $path = null,
        ?string $code = null
    ): void {
        $errors = $errorCollector->getErrors();
        $found  = false;

        foreach ($errors as $error) {
            $messageMatches = str_contains($error['message'], $messagePattern);
            $pathMatches    = $path === null || $error['path'] === $path;
            $codeMatches    = $code === null || $error['code'] === $code;

            if ($messageMatches && $pathMatches && $codeMatches) {
                $found = true;
                break;
            }
        }

        self::assertTrue(
            $found,
            sprintf(
                'Expected error with message containing "%s"%s%s not found in ErrorCollector',
                $messagePattern,
                $path ? " and path \"{$path}\"" : '',
                $code ? " and code \"{$code}\"" : '',
            ),
        );
    }

    /**
     * Assert that an ErrorCollector contains specific warning patterns
     */
    protected function assertWarningCollectorContains(
        ErrorCollector $errorCollector,
        string $messagePattern,
        ?string $path = null
    ): void {
        $warnings = $errorCollector->getWarnings();
        $found    = false;

        foreach ($warnings as $warning) {
            $messageMatches = str_contains($warning['message'], $messagePattern);
            $pathMatches    = $path === null || $warning['path'] === $path;

            if ($messageMatches && $pathMatches) {
                $found = true;
                break;
            }
        }

        self::assertTrue(
            $found,
            sprintf(
                'Expected warning with message containing "%s"%s not found in ErrorCollector',
                $messagePattern,
                $path ? " and path \"{$path}\"" : '',
            ),
        );
    }

    /**
     * Create a temporary directory for test files
     */
    protected function createTempDirectory(): string
    {
        $tempDir = sys_get_temp_dir() . '/fhir-tools-test-' . uniqid();
        mkdir($tempDir, 0777, true);

        return $tempDir;
    }

    /**
     * Clean up temporary directory and its contents
     */
    protected function cleanupTempDirectory(string $tempDir): void
    {
        if (is_dir($tempDir)) {
            $this->removeDirectory($tempDir);
        }
    }

    /**
     * Recursively remove a directory and its contents
     */
    private function removeDirectory(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    /**
     * Assert that a string contains valid PHP code
     */
    protected function assertValidPhpCode(string $code): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'php_syntax_check');
        file_put_contents($tempFile, $code);

        $output     = [];
        $returnCode = 0;
        exec("php -l {$tempFile} 2>&1", $output, $returnCode);

        unlink($tempFile);

        self::assertSame(
            0,
            $returnCode,
            'Generated PHP code contains syntax errors: ' . implode("\n", $output),
        );
    }

    /**
     * Assert that a string follows PSR-12 naming conventions
     */
    protected function assertPsr12ClassName(string $className): void
    {
        self::assertMatchesRegularExpression(
            '/^[A-Z][a-zA-Z0-9]*$/',
            $className,
            "Class name '{$className}' does not follow PSR-12 conventions",
        );
    }

    /**
     * Assert that a namespace follows PSR-4 conventions
     */
    protected function assertPsr4Namespace(string $namespace): void
    {
        self::assertMatchesRegularExpression(
            '/^[A-Z][a-zA-Z0-9]*(\\\[A-Z][a-zA-Z0-9]*)*$/',
            $namespace,
            "Namespace '{$namespace}' does not follow PSR-4 conventions",
        );
    }

    /**
     * Create a mock FHIR StructureDefinition for testing
     */
    protected function createMockStructureDefinition(
        string $resourceType = 'Patient',
        string $url = 'http://example.org/StructureDefinition/TestPatient'
    ): array {
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'version'        => '1.0.0',
            'name'           => $resourceType,
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => $resourceType,
            'baseDefinition' => "http://hl7.org/fhir/StructureDefinition/{$resourceType}",
            'derivation'     => 'constraint',
            'differential'   => [
                'element' => [
                    [
                        'path' => $resourceType,
                        'min'  => 0,
                        'max'  => '*',
                    ],
                    [
                        'path' => "{$resourceType}.name",
                        'min'  => 1,
                        'max'  => '*',
                        'type' => [['code' => 'HumanName']],
                    ],
                ],
            ],
        ];
    }

    /**
     * Assert that generated code contains required PHPDoc elements
     */
    protected function assertContainsPhpDoc(string $code, array $expectedTags = []): void
    {
        self::assertStringContainsString('/**', $code, 'Generated code missing PHPDoc block');
        self::assertStringContainsString('*/', $code, 'Generated code missing PHPDoc block end');

        foreach ($expectedTags as $tag) {
            self::assertStringContainsString("@{$tag}", $code, "Generated code missing @{$tag} tag");
        }
    }

    /**
     * Assert that generated code uses strict types
     */
    protected function assertUsesStrictTypes(string $code): void
    {
        self::assertStringContainsString(
            'declare(strict_types=1);',
            $code,
            'Generated code must use strict types declaration',
        );
    }
}
