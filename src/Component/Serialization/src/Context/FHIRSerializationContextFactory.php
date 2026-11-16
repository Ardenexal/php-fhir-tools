<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Context;

use Seld\JsonLint\JsonParser;
use Symfony\Component\Serializer\Encoder\JsonDecode;

/**
 * Factory for creating FHIR serialization contexts with appropriate defaults.
 *
 * This factory provides convenient methods for creating serialization contexts
 * with FHIR-specific defaults and validation rules.
 *
 * @author Ardenexal
 */
class FHIRSerializationContextFactory
{
    /**
     * Create a default FHIR serialization context for JSON format.
     *
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createJsonContext(array $overrides = []): array
    {
        $defaults = [
            'format'                      => 'json',
            'skip_null_values'            => true,
            'preserve_empty_objects'      => false,
            'enable_max_depth'            => true,
            'max_depth'                   => 10,
            'fhir_strict_validation'      => true,
            'fhir_include_extensions'     => true,
            'fhir_include_metadata'       => true,
            'fhir_unknown_element_policy' => 'ignore',
            'fhir_validate_references'    => false,

            // Route JSON parse failures through seld/jsonlint. PHP's own json_last_error_msg()
            // returns a bare "Syntax error" with no position at all, which is useless both to a
            // caller debugging a payload and to the validator, which needs a location to attach a
            // violation to. jsonlint reports the line, a caret column and the expected tokens.
            //
            // Gated on the class actually being loadable, NOT hardcoded true. seld/jsonlint is a hard
            // `require` of this package, so this is normally always on — but Symfony throws
            // UnsupportedException when the flag is set and the class is missing, which would replace
            // the caller's real parse error with a message about our dependency configuration. That is
            // not hypothetical: `demo/` maintains its own vendor tree and psr-4 map, so a new root
            // runtime dependency does not reach it until it is added there too. Degrading to Symfony's
            // plain message is always better than misreporting a malformed payload as a setup fault.
            JsonDecode::DETAILED_ERROR_MESSAGES => class_exists(JsonParser::class),
        ];

        return array_merge($defaults, $overrides);
    }

    /**
     * Create a default FHIR serialization context for XML format.
     *
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createXmlContext(array $overrides = []): array
    {
        $defaults = [
            'format'                      => 'xml',
            'skip_null_values'            => true,
            'preserve_empty_objects'      => false,
            'enable_max_depth'            => true,
            'max_depth'                   => 10,
            'fhir_strict_validation'      => true,
            'fhir_include_extensions'     => true,
            'fhir_include_metadata'       => true,
            'fhir_unknown_element_policy' => 'ignore',
            'fhir_validate_references'    => false,
            'fhir_xml_namespace'          => 'http://hl7.org/fhir',
            'fhir_xml_schema_validation'  => false,
        ];

        return array_merge($defaults, $overrides);
    }

    /**
     * Create a lenient FHIR serialization context for development/testing.
     *
     * @param string               $format    The serialization format ('json' or 'xml')
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createLenientContext(string $format = 'json', array $overrides = []): array
    {
        $baseContext = $format === 'xml' ? $this->createXmlContext() : $this->createJsonContext();

        $lenientOverrides = [
            'fhir_strict_validation'      => false,
            'fhir_unknown_element_policy' => 'preserve',
            'fhir_validate_references'    => false,
        ];

        return array_merge($baseContext, $lenientOverrides, $overrides);
    }

    /**
     * Create a strict FHIR serialization context for production use.
     *
     * @param string               $format    The serialization format ('json' or 'xml')
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createStrictContext(string $format = 'json', array $overrides = []): array
    {
        $baseContext = $format === 'xml' ? $this->createXmlContext() : $this->createJsonContext();

        $strictOverrides = [
            'fhir_strict_validation'      => true,
            'fhir_unknown_element_policy' => 'error',
            'fhir_validate_references'    => true,
        ];

        return array_merge($baseContext, $strictOverrides, $overrides);
    }

    /**
     * Create a context optimized for performance (minimal validation).
     *
     * @param string               $format    The serialization format ('json' or 'xml')
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createPerformanceContext(string $format = 'json', array $overrides = []): array
    {
        $baseContext = $format === 'xml' ? $this->createXmlContext() : $this->createJsonContext();

        $performanceOverrides = [
            'fhir_strict_validation'      => false,
            'fhir_include_metadata'       => false,
            'fhir_validate_references'    => false,
            'fhir_unknown_element_policy' => 'ignore',
            'enable_max_depth'            => false,
        ];

        return array_merge($baseContext, $performanceOverrides, $overrides);
    }

    /**
     * Create a context for debugging with detailed information.
     *
     * @param string               $format    The serialization format ('json' or 'xml')
     * @param array<string, mixed> $overrides Additional context options to override defaults
     *
     * @return array<string, mixed>
     */
    public function createDebugContext(string $format = 'json', array $overrides = []): array
    {
        $baseContext = $format === 'xml' ? $this->createXmlContext() : $this->createJsonContext();

        $debugOverrides = [
            'fhir_debug_mode'          => true,
            'fhir_include_debug_info'  => true,
            'fhir_trace_serialization' => true,
        ];

        return array_merge($baseContext, $debugOverrides, $overrides);
    }
}
