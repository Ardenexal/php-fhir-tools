<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Exception;

use Ardenexal\FHIRTools\Exception\GenerationException;
use Ardenexal\FHIRTools\Exception\PackageException;
use Ardenexal\FHIRTools\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for FHIR Tools exception hierarchy
 *
 * This test class verifies the complete functionality of the FHIR Tools
 * exception system, including all exception types and their factory methods.
 *
 * Test Coverage:
 * - PackageException factory methods and context
 * - GenerationException factory methods and context
 * - ValidationException factory methods and context
 * - Base exception context management
 * - Formatted message generation
 * - Exception chaining and error codes
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRToolsExceptionTest extends TestCase
{
    /**
     * Test PackageException factory method for package not found scenarios
     */
    public function testPackageExceptionPackageNotFound(): void
    {
        $exception = PackageException::packageNotFound('test-package', '1.0.0');

        self::assertStringContainsString('test-package', $exception->getMessage());
        self::assertStringContainsString('1.0.0', $exception->getMessage());
        self::assertSame(404, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('test-package', $context['package_name']);
        self::assertSame('1.0.0', $context['version']);
        self::assertSame('package_not_found', $context['error_type']);
    }

    public function testPackageExceptionDownloadFailed(): void
    {
        $exception = PackageException::downloadFailed('test-package', '1.0.0', 500);

        self::assertStringContainsString('test-package', $exception->getMessage());
        self::assertStringContainsString('1.0.0', $exception->getMessage());
        self::assertStringContainsString('500', $exception->getMessage());
        self::assertSame(500, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('test-package', $context['package_name']);
        self::assertSame('1.0.0', $context['version']);
        self::assertSame(500, $context['http_status']);
    }

    public function testGenerationExceptionInvalidStructureDefinition(): void
    {
        $exception = GenerationException::invalidStructureDefinition('http://example.com/test', 'Missing required field');

        self::assertStringContainsString('http://example.com/test', $exception->getMessage());
        self::assertStringContainsString('Missing required field', $exception->getMessage());
        self::assertSame(400, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('http://example.com/test', $context['structure_definition_url']);
        self::assertSame('Missing required field', $context['reason']);
    }

    public function testGenerationExceptionMissingContentReference(): void
    {
        $exception = GenerationException::missingContentReference('#Patient.name', 'Patient.name.family');

        self::assertStringContainsString('#Patient.name', $exception->getMessage());
        self::assertStringContainsString('Patient.name.family', $exception->getMessage());
        self::assertSame(404, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('#Patient.name', $context['content_reference']);
        self::assertSame('Patient.name.family', $context['element_path']);
    }

    public function testGenerationExceptionUnsupportedFhirVersion(): void
    {
        $exception = GenerationException::unsupportedFhirVersion('6.0.0');

        self::assertStringContainsString('6.0.0', $exception->getMessage());
        self::assertSame(400, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('6.0.0', $context['fhir_version']);
    }

    public function testValidationExceptionInvalidResourceType(): void
    {
        $exception = ValidationException::invalidResourceType('Patient', 'Observation');

        self::assertStringContainsString('Patient', $exception->getMessage());
        self::assertStringContainsString('Observation', $exception->getMessage());
        self::assertSame(400, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('Patient', $context['resource_type']);
        self::assertSame('Observation', $context['expected_type']);
    }

    public function testValidationExceptionMissingRequiredField(): void
    {
        $exception = ValidationException::missingRequiredField('name', 'Patient.name');

        self::assertStringContainsString('name', $exception->getMessage());
        self::assertStringContainsString('Patient.name', $exception->getMessage());
        self::assertSame(400, $exception->getCode());

        $context = $exception->getContext();
        self::assertSame('name', $context['field_name']);
        self::assertSame('Patient.name', $context['element_path']);
    }

    public function testExceptionContextManipulation(): void
    {
        $exception = GenerationException::invalidElementPath('invalid.path');

        $exception->addContext('additional_info', 'test value');

        $context = $exception->getContext();
        self::assertSame('test value', $context['additional_info']);
        self::assertSame('invalid.path', $context['element_path']);
    }

    public function testFormattedMessage(): void
    {
        $exception = PackageException::packageNotFound('test-package');

        $formattedMessage = $exception->getFormattedMessage();

        self::assertStringContainsString('test-package', $formattedMessage);
        self::assertStringContainsString('Context:', $formattedMessage);
        self::assertStringContainsString('package_name', $formattedMessage);
    }
}
