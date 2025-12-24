<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageMetadata;
use Ardenexal\FHIRTools\Exception\PackageException;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

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

        // Create required dependencies for the command
        $filesystem = new Filesystem();
        $context    = new BuilderContext();

        // Set up namespaces for the context
        $elementNamespace   = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Element');
        $enumNamespace      = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Enum');
        $primitiveNamespace = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Primitive');
        $datatypeNamespace  = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\DataType');
        $context->addElementNamespace('R4B', $elementNamespace);
        $context->addEnumNamespace('R4B', $enumNamespace);
        $context->addPrimitiveNamespace('R4B', $primitiveNamespace);
        $context->addDatatypeNamespace('R4B', $datatypeNamespace);

        // Mock the PackageLoader to avoid network calls
        $packageLoader = $this->createMock(PackageLoader::class);
        $packageLoader->method('installPackage')->willReturn(new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['4.3.0'],
            url: 'http://example.org/test-package',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Package',
        ));

        // Add commands to application
        $this->application->addCommand(new FHIRModelGeneratorCommand($filesystem, $context, $packageLoader));
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
            '--package' => ['hl7.fhir.r4b.core'],
        ]);

        $output     = $commandTester->getDisplay();
        $statusCode = $commandTester->getStatusCode();

        if ($statusCode !== 0) {
            echo "Command output:\n" . $output . "\n";
            echo 'Status code: ' . $statusCode . "\n";
        }

        self::assertSame(0, $statusCode, 'Command should exit with success code');
        self::assertStringContainsString('FHIR model generation completed successfully', $output);
    }

    /**
     * Test command execution with invalid arguments
     */
    public function testCommandExecutionWithInvalidArguments(): void
    {
        // Create a separate application with a PackageLoader that throws exceptions for invalid packages
        $application = new Application();
        $filesystem  = new Filesystem();
        $context     = new BuilderContext();

        $packageLoader = $this->createMock(PackageLoader::class);
        $packageLoader->method('installPackage')
            ->willThrowException(PackageException::packageNotFound('invalid.package.name'));

        $application->addCommand(new FHIRModelGeneratorCommand($filesystem, $context, $packageLoader));

        $command       = $application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            '--package' => ['invalid.package.name'],
        ]);

        self::assertNotSame(0, $commandTester->getStatusCode(), 'Command should exit with error code');

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Failed to process package', $output);
    }

    /**
     * Test command execution with verbose output
     */
    public function testCommandExecutionWithVerboseOutput(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            '--package' => ['hl7.fhir.r4b.core'],
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Loading', $output);
    }

    /**
     * Test command execution with multiple packages
     */
    public function testCommandExecutionWithMultiplePackages(): void
    {
        $command       = $this->application->find('fhir:generate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            '--package' => ['hl7.fhir.r4b.core', 'hl7.terminology.r4'],
        ]);

        self::assertSame(0, $commandTester->getStatusCode());

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Loading FHIR Implementation Guide packages', $output);
    }

    /**
     * Test command registration and basic properties
     */
    public function testCommandRegistration(): void
    {
        $command = $this->application->find('fhir:generate');

        self::assertInstanceOf(FHIRModelGeneratorCommand::class, $command);
        self::assertSame('fhir:generate', $command->getName());
        self::assertStringContainsString('Generates FHIR model classes', $command->getDescription());
    }
}
