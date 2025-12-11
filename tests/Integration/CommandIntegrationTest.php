<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Integration tests for Symfony Console commands
 *
 * This test class verifies the complete command execution workflow
 * including argument parsing, service integration, and output formatting.
 *
 * Test Coverage:
 * - Command execution with various arguments
 * - Error handling and exit codes
 * - Output formatting and verbosity levels
 * - Integration with FHIR generation services
 * - Performance monitoring
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class CommandIntegrationTest extends TestCase
{
    private Application $application;

    private string $tempOutputDir;

    protected function setUp(): void
    {
        $this->application   = new Application();
        $this->tempOutputDir = $this->createTempDirectory();

        // Add commands to application
        $this->application->add(new FHIRModelGeneratorCommand());
    }

    protected function tearDown(): void
    {
        $this->cleanupTempDirectory($this->tempOutputDir);
    }

    /**
     * Test successful command execution
     */
    public function testSuccessfulCommandExecution(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--test-mode'  => true, // Use test fixtures instead of downloading
        ]);

        self::assertSame(0, $commandTester->getStatusCode(), 'Command should exit with success code');

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Generation completed successfully', $output);
    }

    /**
     * Test command execution with invalid arguments
     */
    public function testCommandExecutionWithInvalidArguments(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'      => 'INVALID_VERSION',
            '--output-dir' => $this->tempOutputDir,
        ]);

        self::assertNotSame(0, $commandTester->getStatusCode(), 'Command should exit with error code');

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Unsupported FHIR version', $output);
    }

    /**
     * Test command execution with verbose output
     */
    public function testCommandExecutionWithVerboseOutput(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--test-mode'  => true,
        ], ['verbosity' => CommandTester::VERBOSITY_VERBOSE]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Processing StructureDefinition', $output);
        self::assertStringContainsString('Generated class', $output);
    }

    /**
     * Test command execution with dry-run option
     */
    public function testCommandExecutionWithDryRun(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--dry-run'    => true,
            '--test-mode'  => true,
        ]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Dry run mode', $output);
        self::assertStringContainsString('Would generate', $output);

        // No files should be created in dry-run mode
        $files = glob($this->tempOutputDir . '/*.php');
        self::assertEmpty($files, 'No files should be generated in dry-run mode');
    }

    /**
     * Test command execution with custom namespace
     */
    public function testCommandExecutionWithCustomNamespace(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $customNamespace = 'Custom\\FHIR\\R4B';

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--namespace'  => $customNamespace,
            '--test-mode'  => true,
        ]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("Using namespace: {$customNamespace}", $output);
    }

    /**
     * Test command execution with progress reporting
     */
    public function testCommandExecutionWithProgressReporting(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'         => 'R4B',
            '--output-dir'    => $this->tempOutputDir,
            '--show-progress' => true,
            '--test-mode'     => true,
        ]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        // Progress bar output contains special characters
        self::assertStringContainsString('Processing', $output);
    }

    /**
     * Test command execution with file filtering
     */
    public function testCommandExecutionWithFileFiltering(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--filter'     => 'Patient',
            '--test-mode'  => true,
        ]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Filtering resources: Patient', $output);
    }

    /**
     * Test command help output
     */
    public function testCommandHelpOutput(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['--help' => true]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Generate FHIR model classes', $output);
        self::assertStringContainsString('Arguments:', $output);
        self::assertStringContainsString('Options:', $output);
    }

    /**
     * Test command execution with missing required arguments
     */
    public function testCommandExecutionWithMissingArguments(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        self::assertNotSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Not enough arguments', $output);
    }

    /**
     * Test command execution with invalid output directory
     */
    public function testCommandExecutionWithInvalidOutputDirectory(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $invalidDir = '/invalid/directory/path';

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $invalidDir,
        ]);

        self::assertNotSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Invalid output directory', $output);
    }

    /**
     * Test command execution performance
     */
    public function testCommandExecutionPerformance(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $startTime = microtime(true);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--test-mode'  => true,
        ]);

        $executionTime = microtime(true) - $startTime;

        self::assertSame(0, $commandTester->getStatusCode());

        // Command should complete within reasonable time (30 seconds for test mode)
        self::assertLessThan(30.0, $executionTime, 'Command execution should complete within 30 seconds');
    }

    /**
     * Test command execution with memory monitoring
     */
    public function testCommandExecutionMemoryUsage(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $initialMemory = memory_get_usage(true);

        $commandTester->execute([
            'version'      => 'R4B',
            '--output-dir' => $this->tempOutputDir,
            '--test-mode'  => true,
        ]);

        $finalMemory    = memory_get_usage(true);
        $memoryIncrease = $finalMemory - $initialMemory;

        self::assertSame(0, $commandTester->getStatusCode());

        // Memory usage should be reasonable (less than 100MB for test mode)
        self::assertLessThan(
            100 * 1024 * 1024,
            $memoryIncrease,
            'Command memory usage should remain reasonable',
        );
    }
}
