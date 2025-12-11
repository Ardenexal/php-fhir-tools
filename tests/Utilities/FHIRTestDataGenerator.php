<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Utilities;

use Eris\Generator;

/**
 * FHIR-specific test data generator using Eris property-based testing
 *
 * This class provides generators for creating valid and invalid FHIR data
 * structures for comprehensive testing of FHIR processing functionality.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRTestDataGenerator
{
    /**
     * Generate valid FHIR resource types
     */
    public static function fhirResourceType(): Generator
    {
        return Generator\elements([
            'Patient',
            'Observation',
            'Practitioner',
            'Organization',
            'Encounter',
            'Condition',
            'Procedure',
            'MedicationRequest',
            'DiagnosticReport',
            'AllergyIntolerance',
        ]);
    }

    /**
     * Generate valid FHIR primitive types
     */
    public static function fhirPrimitiveType(): Generator
    {
        return Generator\elements([
            'boolean',
            'integer',
            'string',
            'decimal',
            'uri',
            'url',
            'canonical',
            'base64Binary',
            'instant',
            'date',
            'dateTime',
            'time',
            'code',
            'oid',
            'id',
            'markdown',
            'unsignedInt',
            'positiveInt',
        ]);
    }

    /**
     * Generate valid FHIR cardinality strings
     */
    public static function fhirCardinality(): Generator
    {
        return Generator\elements([
            '0..1',
            '1..1',
            '0..*',
            '1..*',
            '0..0',
            '2..5',
            '1..10',
        ]);
    }

    /**
     * Generate invalid FHIR cardinality strings for negative testing
     */
    public static function invalidFhirCardinality(): Generator
    {
        return Generator\elements([
            '2..0',    // min > max
            '-1..1',   // negative min
            '1..-1',   // negative max
            'abc',     // non-numeric
            '1..2..3', // malformed
            '',        // empty
            '1..',     // incomplete
            '..1',     // incomplete
        ]);
    }

    /**
     * Generate FHIR element paths
     */
    public static function fhirElementPath(): Generator
    {
        return Generator\bind(
            self::fhirResourceType(),
            static fn ($resourceType) => Generator\bind(
                Generator\elements(['name', 'identifier', 'status', 'code', 'value']),
                static fn ($element) => Generator\constant("{$resourceType}.{$element}"),
            ),
        );
    }

    /**
     * Generate complex FHIR element paths with nested elements
     */
    public static function complexFhirElementPath(): Generator
    {
        return Generator\bind(
            self::fhirResourceType(),
            static fn ($resourceType) => Generator\bind(
                Generator\elements(['name.family', 'identifier.value', 'code.coding.system']),
                static fn ($element) => Generator\constant("{$resourceType}.{$element}"),
            ),
        );
    }

    /**
     * Generate FHIR URLs
     */
    public static function fhirUrl(): Generator
    {
        return Generator\bind(
            Generator\elements(['http://hl7.org/fhir', 'http://terminology.hl7.org', 'http://example.org']),
            static fn ($base) => Generator\bind(
                Generator\elements(['StructureDefinition', 'ValueSet', 'CodeSystem']),
                static fn ($type) => Generator\bind(
                    Generator\string()->withCharset(Generator\charset()->alphanumeric()),
                    static fn ($id) => Generator\constant("{$base}/{$type}/{$id}"),
                ),
            ),
        );
    }

    /**
     * Generate FHIR version strings
     */
    public static function fhirVersion(): Generator
    {
        return Generator\elements([
            '4.0.1',
            '4.3.0',
            '5.0.0',
            '6.0.0-cibuild',
        ]);
    }

    /**
     * Generate invalid FHIR version strings for negative testing
     */
    public static function invalidFhirVersion(): Generator
    {
        return Generator\elements([
            '3.0.0',     // too old
            '7.0.0',     // too new
            'invalid',   // non-numeric
            '',          // empty
            '4',         // incomplete
            '4.0',       // incomplete
        ]);
    }

    /**
     * Generate FHIR package names
     */
    public static function fhirPackageName(): Generator
    {
        return Generator\bind(
            Generator\elements(['hl7.fhir', 'hl7.terminology', 'example.package']),
            static fn ($prefix) => Generator\bind(
                Generator\elements(['r4', 'r4b', 'r5']),
                static fn ($version) => Generator\constant("{$prefix}.{$version}"),
            ),
        );
    }

    /**
     * Generate FHIR structure definition basic structure
     */
    public static function fhirStructureDefinition(): Generator
    {
        return Generator\bind(
            self::fhirUrl(),
            static fn ($url) => Generator\bind(
                self::fhirResourceType(),
                static fn ($type) => Generator\bind(
                    self::fhirVersion(),
                    static fn ($version) => Generator\constant([
                        'resourceType'   => 'StructureDefinition',
                        'url'            => $url,
                        'version'        => $version,
                        'name'           => $type,
                        'status'         => 'active',
                        'kind'           => 'resource',
                        'abstract'       => false,
                        'type'           => $type,
                        'baseDefinition' => "http://hl7.org/fhir/StructureDefinition/{$type}",
                        'derivation'     => 'constraint',
                    ]),
                ),
            ),
        );
    }

    /**
     * Generate FHIR element definition
     */
    public static function fhirElementDefinition(): Generator
    {
        return Generator\bind(
            self::fhirElementPath(),
            static fn ($path) => Generator\bind(
                self::fhirCardinality(),
                static fn ($cardinality) => Generator\bind(
                    self::fhirPrimitiveType(),
                    static fn ($type) => Generator\constant([
                        'path' => $path,
                        'min'  => (int) explode('..', $cardinality)[0],
                        'max'  => explode('..', $cardinality)[1],
                        'type' => [['code' => $type]],
                    ]),
                ),
            ),
        );
    }

    /**
     * Generate error codes for testing
     */
    public static function errorCode(): Generator
    {
        return Generator\elements([
            'VALIDATION_ERROR',
            'GENERATION_ERROR',
            'PACKAGE_ERROR',
            'NETWORK_ERROR',
            'FILE_ERROR',
            'PARSE_ERROR',
        ]);
    }

    /**
     * Generate HTTP status codes
     */
    public static function httpStatusCode(): Generator
    {
        return Generator\elements([
            200, 201, 204, 400, 401, 403, 404, 500, 502, 503, 504,
        ]);
    }
}
