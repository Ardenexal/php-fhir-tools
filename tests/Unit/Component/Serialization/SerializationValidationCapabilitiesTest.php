<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRSchemaValidator;
use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for Serialization component validation capabilities.
 *
 * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
 *
 * Tests that the Serialization component provides complete FHIR validation functionality.
 *
 * @author Kiro AI Assistant
 */
class SerializationValidationCapabilitiesTest extends TestCase
{
    use TestTrait;

    /**
     * Test that FHIR validator can validate objects and return errors.
     *
     * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
     * **Validates: Requirements 8.4**
     */
    public function testFHIRValidatorCanValidateObjectsAndReturnErrors(): void
    {
        $this->forAll(
            Generator\bool(), // isValidObject
            Generator\elements(['resource', 'complex', 'primitive', 'backbone', 'unknown']),
        )->then(function(bool $isValidObject, string $objectType): void {
            // Create mock metadata extractor
            $metadataExtractor = $this->createMock(FHIRMetadataExtractorInterface::class);

            // Configure mock based on object type
            $metadataExtractor->method('isResource')->willReturn($objectType === 'resource');
            $metadataExtractor->method('isComplexType')->willReturn($objectType === 'complex');
            $metadataExtractor->method('isPrimitiveType')->willReturn($objectType === 'primitive');
            $metadataExtractor->method('isBackboneElement')->willReturn($objectType === 'backbone');

            if ($objectType === 'resource') {
                $metadataExtractor->method('extractResourceType')->willReturn($isValidObject ? 'Patient' : null);
            }

            // Create validator
            $validator = new FHIRValidator($metadataExtractor);

            // Create test object
            $testObject = new \stdClass();

            // Validate the object
            $errors = $validator->validate($testObject);

            // Verify validation behavior
            self::assertIsArray($errors);

            if ($objectType === 'unknown') {
                // Unknown objects should have validation errors
                self::assertNotEmpty($errors);
                self::assertStringContainsString('not a valid FHIR object', $errors[0]);
            } elseif ($objectType === 'resource' && !$isValidObject) {
                // Invalid resources should have validation errors
                self::assertNotEmpty($errors);
                self::assertStringContainsString('missing resourceType', $errors[0]);
            } else {
                // Valid objects should pass validation
                self::assertEmpty($errors);
            }
        });
    }

    /**
     * Test that FHIR validator can throw exceptions for invalid objects.
     *
     * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
     * **Validates: Requirements 8.4**
     */
    public function testFHIRValidatorCanThrowExceptionsForInvalidObjects(): void
    {
        $this->forAll(
            Generator\bool(), // shouldThrowException
        )->then(function(bool $shouldThrowException): void {
            // Create mock metadata extractor
            $metadataExtractor = $this->createMock(FHIRMetadataExtractorInterface::class);

            if ($shouldThrowException) {
                // Configure to return invalid object
                $metadataExtractor->method('isResource')->willReturn(false);
                $metadataExtractor->method('isComplexType')->willReturn(false);
                $metadataExtractor->method('isPrimitiveType')->willReturn(false);
                $metadataExtractor->method('isBackboneElement')->willReturn(false);
            } else {
                // Configure to return valid object
                $metadataExtractor->method('isResource')->willReturn(true);
                $metadataExtractor->method('extractResourceType')->willReturn('Patient');
            }

            // Create validator
            $validator = new FHIRValidator($metadataExtractor);

            // Create test object
            $testObject = new \stdClass();

            if ($shouldThrowException) {
                // Should throw ValidationException for invalid objects
                $this->expectException(ValidationException::class);
                $this->expectExceptionMessage('FHIR validation failed');
                $validator->validateOrThrow($testObject);
            } else {
                // Should not throw exception for valid objects
                $validator->validateOrThrow($testObject);
                self::assertTrue(true); // Test passed if no exception thrown
            }
        });
    }

    /**
     * Test that FHIR schema validator can validate XML and return errors.
     *
     * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
     * **Validates: Requirements 8.4**
     */
    public function testFHIRSchemaValidatorCanValidateXMLAndReturnErrors(): void
    {
        $this->forAll(
            Generator\elements([
                '<?xml version="1.0"?><Patient><name>Test</name></Patient>',
                '<?xml version="1.0"?><InvalidXML><unclosed>',
                '<Patient><name>Test</name></Patient>',
                'not xml at all',
            ]),
        )->then(function(string $xmlData): void {
            $validator = new FHIRSchemaValidator();

            // Test well-formed check
            $wellFormedErrors = $validator->checkWellFormed($xmlData);
            self::assertIsArray($wellFormedErrors);

            // Test schema validation (will return error about missing schema)
            $schemaErrors = $validator->validateXml($xmlData);
            self::assertIsArray($schemaErrors);

            // Verify error behavior based on XML content
            if (str_contains($xmlData, '<unclosed>') || $xmlData === 'not xml at all') {
                // Invalid XML should have well-formed errors
                self::assertNotEmpty($wellFormedErrors);
            } elseif (str_starts_with($xmlData, '<?xml')) {
                // Valid XML should pass well-formed check
                self::assertEmpty($wellFormedErrors);
                // But should have schema validation error (no schema provided)
                self::assertNotEmpty($schemaErrors);
                self::assertStringContainsString('Schema file not found', $schemaErrors[0]);
            }
        });
    }

    /**
     * Test that FHIR schema validator can throw exceptions for invalid XML.
     *
     * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
     * **Validates: Requirements 8.4**
     */
    public function testFHIRSchemaValidatorCanThrowExceptionsForInvalidXML(): void
    {
        $this->forAll(
            Generator\elements([
                '<?xml version="1.0"?><Patient><name>Test</name></Patient>',
                '<?xml version="1.0"?><InvalidXML><unclosed>',
            ]),
        )->then(function(string $xmlData): void {
            $validator = new FHIRSchemaValidator();

            if (str_contains($xmlData, '<unclosed>')) {
                // Should throw ValidationException for invalid XML
                $this->expectException(ValidationException::class);
                $this->expectExceptionMessage('FHIR XML schema validation failed');
                $validator->validateXmlOrThrow($xmlData);
            } else {
                // Should throw ValidationException for missing schema
                $this->expectException(ValidationException::class);
                $this->expectExceptionMessage('FHIR XML schema validation failed');
                $validator->validateXmlOrThrow($xmlData);
            }
        });
    }

    /**
     * Test that validation components can be instantiated independently.
     *
     * **Feature: repository-reorganization, Property 23: Serialization validation capabilities**
     * **Validates: Requirements 8.4**
     */
    public function testValidationComponentsCanBeInstantiatedIndependently(): void
    {
        $this->forAll(
            Generator\elements([
                FHIRValidator::class,
                FHIRSchemaValidator::class,
            ]),
        )->then(function(string $validatorClass): void {
            // Verify class exists and can be reflected
            self::assertTrue(class_exists($validatorClass), "Validator class {$validatorClass} should exist");

            $reflection = new \ReflectionClass($validatorClass);
            self::assertTrue($reflection->isInstantiable());

            // Verify namespace is correct for component independence
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Serialization\\Validator', $validatorClass);

            // Test instantiation
            if ($validatorClass === FHIRValidator::class) {
                $metadataExtractor = $this->createMock(FHIRMetadataExtractorInterface::class);
                $validator         = new FHIRValidator($metadataExtractor);
                self::assertInstanceOf(FHIRValidator::class, $validator);
            } else {
                $validator = new FHIRSchemaValidator();
                self::assertInstanceOf(FHIRSchemaValidator::class, $validator);
            }
        });
    }
}
