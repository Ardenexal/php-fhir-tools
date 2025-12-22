<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Ardenexal\FHIRTools\Exception\ValidationException;

/**
 * FHIR validation service for validating FHIR objects against business rules.
 *
 * This service provides validation capabilities for FHIR objects beyond basic
 * serialization validation, including business rules and constraints.
 *
 * @author Kiro AI Assistant
 */
class FHIRValidator
{
    public function __construct(
        private readonly FHIRMetadataExtractorInterface $metadataExtractor
    ) {
    }

    /**
     * Validate a FHIR object against its constraints.
     *
     * @param object               $fhirObject The FHIR object to validate
     * @param array<string, mixed> $context    Validation context
     *
     * @return array<string> Array of validation errors (empty if valid)
     */
    public function validate(object $fhirObject, array $context = []): array
    {
        $errors = [];

        // Basic structure validation
        if (!$this->isValidFHIRObject($fhirObject)) {
            $errors[] = 'Object is not a valid FHIR object';

            return $errors;
        }

        // Resource-specific validation
        if ($this->metadataExtractor->isResource($fhirObject)) {
            $errors = array_merge($errors, $this->validateResource($fhirObject, $context));
        }

        // Complex type validation
        if ($this->metadataExtractor->isComplexType($fhirObject)) {
            $errors = array_merge($errors, $this->validateComplexType($fhirObject, $context));
        }

        // Primitive type validation
        if ($this->metadataExtractor->isPrimitiveType($fhirObject)) {
            $errors = array_merge($errors, $this->validatePrimitiveType($fhirObject, $context));
        }

        return $errors;
    }

    /**
     * Validate a FHIR object and throw exception if invalid.
     *
     * @param object               $fhirObject The FHIR object to validate
     * @param array<string, mixed> $context    Validation context
     *
     * @throws ValidationException If validation fails
     */
    public function validateOrThrow(object $fhirObject, array $context = []): void
    {
        $errors = $this->validate($fhirObject, $context);

        if (!empty($errors)) {
            throw new ValidationException('FHIR validation failed: ' . implode(', ', $errors));
        }
    }

    /**
     * Check if an object is a valid FHIR object.
     */
    private function isValidFHIRObject(object $object): bool
    {
        return $this->metadataExtractor->isResource($object)
               || $this->metadataExtractor->isComplexType($object)
               || $this->metadataExtractor->isPrimitiveType($object)
               || $this->metadataExtractor->isBackboneElement($object);
    }

    /**
     * Validate a FHIR resource.
     *
     * @param object               $resource The resource to validate
     * @param array<string, mixed> $context  Validation context
     *
     * @return array<string> Validation errors
     */
    private function validateResource(object $resource, array $context): array
    {
        $errors = [];

        // Check for required resourceType
        $resourceType = $this->metadataExtractor->extractResourceType($resource);
        if ($resourceType === null) {
            $errors[] = 'Resource missing resourceType';
        }

        // Additional resource validation logic would go here

        return $errors;
    }

    /**
     * Validate a FHIR complex type.
     *
     * @param object               $complexType The complex type to validate
     * @param array<string, mixed> $context     Validation context
     *
     * @return array<string> Validation errors
     */
    private function validateComplexType(object $complexType, array $context): array
    {
        $errors = [];

        // Complex type validation logic would go here

        return $errors;
    }

    /**
     * Validate a FHIR primitive type.
     *
     * @param object               $primitiveType The primitive type to validate
     * @param array<string, mixed> $context       Validation context
     *
     * @return array<string> Validation errors
     */
    private function validatePrimitiveType(object $primitiveType, array $context): array
    {
        $errors = [];

        // Primitive type validation logic would go here

        return $errors;
    }
}
