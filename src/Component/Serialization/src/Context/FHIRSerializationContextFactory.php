<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Context;

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
