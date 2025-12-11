<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Exception;

/**
 * Exception thrown when validation fails
 *
 * This exception is used for all validation-related errors including:
 * - Invalid resource types that don't match expected values
 * - Missing required fields in FHIR structures
 * - Invalid field values that don't match expected types
 * - Multiple validation errors collected during processing
 *
 * Each static factory method provides specific context information
 * to help identify the exact validation failure and its location.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class ValidationException extends FHIRToolsException
{
    /**
     * Create exception for invalid resource type
     *
     * This method creates an exception when a resource has an unexpected type
     * that doesn't match what was expected during validation.
     *
     * @param string $resourceType The actual resource type found
     * @param string $expectedType The expected resource type
     *
     * @return self The configured exception instance
     */
    public static function invalidResourceType(string $resourceType, string $expectedType): self
    {
        return new self(
            "Invalid resource type '{$resourceType}', expected '{$expectedType}'",
            400,
            null,
            [
                'resource_type' => $resourceType,
                'expected_type' => $expectedType,
                'error_type'    => 'invalid_resource_type',
            ],
        );
    }

    /**
     * Create exception for missing required field
     *
     * This method creates an exception when a required field is missing
     * from a FHIR structure during validation.
     *
     * @param string $fieldName   The name of the missing required field
     * @param string $elementPath The path of the element missing the field
     *
     * @return self The configured exception instance
     */
    public static function missingRequiredField(string $fieldName, string $elementPath): self
    {
        return new self(
            "Missing required field '{$fieldName}' in element '{$elementPath}'",
            400,
            null,
            [
                'field_name'   => $fieldName,
                'element_path' => $elementPath,
                'error_type'   => 'missing_required_field',
            ],
        );
    }

    /**
     * Create exception for invalid field value
     *
     * This method creates an exception when a field has a value that doesn't
     * match the expected type during validation.
     *
     * @param string $fieldName    The name of the field with invalid value
     * @param mixed  $value        The actual value that was invalid
     * @param string $expectedType The expected type for the field
     *
     * @return self The configured exception instance
     */
    public static function invalidFieldValue(string $fieldName, mixed $value, string $expectedType): self
    {
        return new self(
            "Invalid value for field '{$fieldName}': expected {$expectedType}, got " . gettype($value),
            400,
            null,
            [
                'field_name'    => $fieldName,
                'actual_value'  => $value,
                'actual_type'   => gettype($value),
                'expected_type' => $expectedType,
                'error_type'    => 'invalid_field_value',
            ],
        );
    }

    /**
     * Create exception for multiple validation errors
     *
     * This method creates an exception when multiple validation errors
     * have been collected and need to be reported together.
     *
     * @param array<mixed> $errors Array of validation errors
     *
     * @return self The configured exception instance
     */
    public static function multipleValidationErrors(array $errors): self
    {
        $errorCount = count($errors);

        return new self(
            "Validation failed with {$errorCount} error(s)",
            400,
            null,
            [
                'errors'      => $errors,
                'error_count' => $errorCount,
                'error_type'  => 'multiple_validation_errors',
            ],
        );
    }
}
