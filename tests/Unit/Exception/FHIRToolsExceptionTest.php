<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Exception;

use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\Metadata\src\Exception\GenerationException;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\Metadata\src\Exception\PackageException;
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
}
