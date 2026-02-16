<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for ErrorCollector functionality
 *
 * This test class verifies the complete functionality of the ErrorCollector
 * class, including error and warning collection, filtering capabilities,
 * and integration with the exception system.
 *
 * Test Coverage:
 * - Error and warning addition with context
 * - Filtering by severity, code, and path
 * - Summary and detailed output generation
 * - Error counting and state management
 * - Exception throwing for fail-fast behavior
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class ErrorCollectorTest extends TestCase
{
    /**
     * ErrorCollector instance under test
     *
     * @var ErrorCollector
     */
    private ErrorCollector $errorCollector;

    /**
     * Set up test environment before each test
     */
    protected function setUp(): void
    {
        $this->errorCollector = new ErrorCollector();
    }

    /**
     * Test adding errors with context information
     */
    public function testAddError(): void
    {
        $this->errorCollector->addError('Test error', 'test.path', 'TEST_CODE');

        self::assertTrue($this->errorCollector->hasErrors());
        self::assertSame(1, $this->errorCollector->getErrorCount());

        $errors = $this->errorCollector->getErrors();
        self::assertCount(1, $errors);
        self::assertSame('Test error', $errors[0]['message']);
        self::assertSame('test.path', $errors[0]['path']);
        self::assertSame('TEST_CODE', $errors[0]['code']);
    }

    /**
     * Test adding warnings with context information
     */
    public function testAddWarning(): void
    {
        $this->errorCollector->addWarning('Test warning', 'test.path');

        self::assertTrue($this->errorCollector->hasWarnings());
        self::assertSame(1, $this->errorCollector->getWarningCount());

        $warnings = $this->errorCollector->getWarnings();
        self::assertCount(1, $warnings);
        self::assertSame('Test warning', $warnings[0]['message']);
        self::assertSame('test.path', $warnings[0]['path']);
    }

    /**
     * Test filtering errors by severity level
     */
    public function testGetErrorsBySeverity(): void
    {
        $this->errorCollector->addError('Critical error', 'test.path', 'CRITICAL', 'critical');
        $this->errorCollector->addError('Normal error', 'test.path', 'NORMAL', 'error');

        $criticalErrors = $this->errorCollector->getErrorsBySeverity('critical');
        self::assertCount(1, $criticalErrors);
        $criticalError = array_values($criticalErrors)[0];
        self::assertSame('Critical error', $criticalError['message']);

        $normalErrors = $this->errorCollector->getErrorsBySeverity('error');
        self::assertCount(1, $normalErrors);
        $normalError = array_values($normalErrors)[0];
        self::assertSame('Normal error', $normalError['message']);
    }

    public function testGetErrorsByCode(): void
    {
        $this->errorCollector->addError('First error', 'test.path', 'CODE_A');
        $this->errorCollector->addError('Second error', 'test.path', 'CODE_B');
        $this->errorCollector->addError('Third error', 'test.path', 'CODE_A');

        $codeAErrors = $this->errorCollector->getErrorsByCode('CODE_A');
        self::assertCount(2, $codeAErrors);

        $codeBErrors = $this->errorCollector->getErrorsByCode('CODE_B');
        self::assertCount(1, $codeBErrors);
    }

    public function testGetErrorsByPath(): void
    {
        $this->errorCollector->addError('Error 1', 'Patient.name', 'CODE_A');
        $this->errorCollector->addError('Error 2', 'Patient.address', 'CODE_B');
        $this->errorCollector->addError('Error 3', 'Observation.value', 'CODE_C');

        $patientErrors = $this->errorCollector->getErrorsByPath('Patient');
        self::assertCount(2, $patientErrors);

        $observationErrors = $this->errorCollector->getErrorsByPath('Observation');
        self::assertCount(1, $observationErrors);
    }

    public function testGetSummary(): void
    {
        self::assertSame('No errors or warnings found.', $this->errorCollector->getSummary());

        $this->errorCollector->addError('Test error');
        self::assertSame('Found 1 error(s).', $this->errorCollector->getSummary());

        $this->errorCollector->addWarning('Test warning');
        self::assertSame('Found 1 error(s) and 1 warning(s).', $this->errorCollector->getSummary());
    }

    public function testClear(): void
    {
        $this->errorCollector->addError('Test error');
        $this->errorCollector->addWarning('Test warning');

        self::assertTrue($this->errorCollector->hasErrors());
        self::assertTrue($this->errorCollector->hasWarnings());

        $this->errorCollector->clear();

        self::assertFalse($this->errorCollector->hasErrors());
        self::assertFalse($this->errorCollector->hasWarnings());
        self::assertSame(0, $this->errorCollector->getErrorCount());
        self::assertSame(0, $this->errorCollector->getWarningCount());
    }

    /**
     * Test exception throwing behavior for fail-fast error handling
     */
    public function testThrowIfErrors(): void
    {
        // Should not throw when no errors
        $this->errorCollector->throwIfErrors();

        // Should throw when errors exist
        $this->errorCollector->addError('Test error');

        $this->expectException(ValidationException::class);
        $this->errorCollector->throwIfErrors();
    }

    public function testGetDetailedOutput(): void
    {
        $this->errorCollector->addError('Test error', 'test.path', 'TEST_CODE');
        $this->errorCollector->addWarning('Test warning', 'test.path');

        $output = $this->errorCollector->getDetailedOutput();

        self::assertStringContainsString('ERRORS:', $output);
        self::assertStringContainsString('Test error', $output);
        self::assertStringContainsString('WARNINGS:', $output);
        self::assertStringContainsString('Test warning', $output);
    }
}
