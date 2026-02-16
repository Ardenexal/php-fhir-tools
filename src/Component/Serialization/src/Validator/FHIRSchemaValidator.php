<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Validator;

use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;

/**
 * FHIR schema validator for validating FHIR XML against XSD schemas.
 *
 * This service provides XML schema validation capabilities for FHIR XML documents
 * against official FHIR XSD schemas.
 *
 * @author Ardenexal
 */
class FHIRSchemaValidator
{
    /**
     * Validate FHIR XML against its schema.
     *
     * @param string      $xmlData    The XML data to validate
     * @param string|null $schemaPath Optional path to specific schema file
     *
     * @return array<string> Array of validation errors (empty if valid)
     */
    public function validateXml(string $xmlData, ?string $schemaPath = null): array
    {
        $errors = [];

        try {
            // Create DOMDocument for validation
            $dom = new \DOMDocument();
            $dom->loadXML($xmlData);

            // Determine schema path if not provided
            if ($schemaPath === null) {
                $schemaPath = $this->detectSchemaPath($xmlData);
            }

            // Validate against schema if available
            if ($schemaPath !== null && file_exists($schemaPath)) {
                libxml_use_internal_errors(true);

                if (!$dom->schemaValidate($schemaPath)) {
                    $xmlErrors = libxml_get_errors();
                    foreach ($xmlErrors as $error) {
                        $errors[] = sprintf(
                            'Line %d: %s',
                            $error->line,
                            trim($error->message),
                        );
                    }
                    libxml_clear_errors();
                }
            } else {
                $errors[] = 'Schema file not found or not specified';
            }
        } catch (\Exception $e) {
            $errors[] = 'XML parsing error: ' . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Validate FHIR XML and throw exception if invalid.
     *
     * @param string      $xmlData    The XML data to validate
     * @param string|null $schemaPath Optional path to specific schema file
     *
     * @throws ValidationException If validation fails
     */
    public function validateXmlOrThrow(string $xmlData, ?string $schemaPath = null): void
    {
        $errors = $this->validateXml($xmlData, $schemaPath);

        if (!empty($errors)) {
            throw new ValidationException('FHIR XML schema validation failed: ' . implode(', ', $errors));
        }
    }

    /**
     * Check if XML is well-formed (basic XML validation).
     *
     * @param string $xmlData The XML data to check
     *
     * @return array<string> Array of XML errors (empty if well-formed)
     */
    public function checkWellFormed(string $xmlData): array
    {
        $errors = [];

        try {
            libxml_use_internal_errors(true);

            $dom = new \DOMDocument();
            if (!$dom->loadXML($xmlData)) {
                $xmlErrors = libxml_get_errors();
                foreach ($xmlErrors as $error) {
                    $errors[] = sprintf(
                        'Line %d: %s',
                        $error->line,
                        trim($error->message),
                    );
                }
                libxml_clear_errors();
            }
        } catch (\Exception $e) {
            $errors[] = 'XML parsing error: ' . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Detect the appropriate schema path based on XML content.
     */
    private function detectSchemaPath(string $xmlData): null
    {
        // This would need to be implemented based on actual FHIR schema locations
        // For now, return null to indicate schema detection is not implemented
        return null;
    }
}
