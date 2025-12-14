<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Configuration context for FHIR serialization operations.
 *
 * This class provides configuration options for customizing FHIR serialization
 * behavior including format selection, validation modes, unknown element policies,
 * performance optimizations, and debugging support.
 *
 * @author Kiro AI Assistant
 */
class FHIRSerializationContext
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';

    public const VALIDATION_STRICT = 'strict';
    public const VALIDATION_LENIENT = 'lenient';

    public const UNKNOWN_POLICY_IGNORE = 'ignore';
    public const UNKNOWN_POLICY_ERROR = 'error';
    public const UNKNOWN_POLICY_PRESERVE = 'preserve';

    /**
     * @param string                $format                 Serialization format (json or xml)
     * @param string                $validationMode         Validation mode (strict or lenient)
     * @param string                $unknownElementPolicy   Policy for handling unknown elements
     * @param bool                  $includeExtensions      Whether to include FHIR extensions
     * @param bool                  $includeMetadata        Whether to include metadata elements
     * @param bool                  $skipNonEssentialValidation Whether to skip non-essential validation for performance
     * @param bool                  $enableDebugInfo        Whether to provide detailed debug information
     * @param bool                  $validateReferences     Whether to validate FHIR references
     * @param bool                  $omitNullValues         Whether to omit null values from output
     * @param bool                  $omitEmptyArrays        Whether to omit empty arrays from output
     * @param bool                  $enableXmlNamespaces    Whether to include XML namespaces (XML format only)
     * @param bool                  $enableXmlSchemaValidation Whether to validate against XML schemas (XML format only)
     * @param array<string, mixed>  $customOptions          Additional custom configuration options
     */
    public function __construct(
        public readonly string $format = self::FORMAT_JSON,
        public readonly string $validationMode = self::VALIDATION_LENIENT,
        public readonly string $unknownElementPolicy = self::UNKNOWN_POLICY_IGNORE,
        public readonly bool $includeExtensions = true,
        public readonly bool $includeMetadata = true,
        public readonly bool $skipNonEssentialValidation = false,
        public readonly bool $enableDebugInfo = false,
        public readonly bool $validateReferences = false,
        public readonly bool $omitNullValues = true,
        public readonly bool $omitEmptyArrays = true,
        public readonly bool $enableXmlNamespaces = true,
        public readonly bool $enableXmlSchemaValidation = false,
        public readonly array $customOptions = []
    ) {
        $this->validateConfiguration();
    }

    /**
     * Create a context for JSON serialization with default settings.
     */
    public static function forJson(): self
    {
        return new self(format: self::FORMAT_JSON);
    }

    /**
     * Create a context for XML serialization with default settings.
     */
    public static function forXml(): self
    {
        return new self(
            format: self::FORMAT_XML,
            enableXmlNamespaces: true
        );
    }

    /**
     * Create a context with strict validation enabled.
     */
    public static function withStrictValidation(): self
    {
        return new self(
            validationMode: self::VALIDATION_STRICT,
            validateReferences: true,
            skipNonEssentialValidation: false
        );
    }

    /**
     * Create a context with lenient validation for performance.
     */
    public static function withLenientValidation(): self
    {
        return new self(
            validationMode: self::VALIDATION_LENIENT,
            validateReferences: false,
            skipNonEssentialValidation: true
        );
    }

    /**
     * Create a context with debugging enabled.
     */
    public static function withDebugging(): self
    {
        return new self(enableDebugInfo: true);
    }

    /**
     * Create a context that preserves unknown elements.
     */
    public static function preservingUnknownElements(): self
    {
        return new self(unknownElementPolicy: self::UNKNOWN_POLICY_PRESERVE);
    }

    /**
     * Create a context that errors on unknown elements.
     */
    public static function erroringOnUnknownElements(): self
    {
        return new self(unknownElementPolicy: self::UNKNOWN_POLICY_ERROR);
    }

    /**
     * Create a new context with modified format.
     */
    public function withFormat(string $format): self
    {
        return new self(
            format: $format,
            validationMode: $this->validationMode,
            unknownElementPolicy: $this->unknownElementPolicy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $this->skipNonEssentialValidation,
            enableDebugInfo: $this->enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: $this->customOptions
        );
    }

    /**
     * Create a new context with modified validation mode.
     */
    public function withValidationMode(string $validationMode): self
    {
        return new self(
            format: $this->format,
            validationMode: $validationMode,
            unknownElementPolicy: $this->unknownElementPolicy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $this->skipNonEssentialValidation,
            enableDebugInfo: $this->enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: $this->customOptions
        );
    }

    /**
     * Create a new context with modified unknown element policy.
     */
    public function withUnknownElementPolicy(string $policy): self
    {
        return new self(
            format: $this->format,
            validationMode: $this->validationMode,
            unknownElementPolicy: $policy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $this->skipNonEssentialValidation,
            enableDebugInfo: $this->enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: $this->customOptions
        );
    }

    /**
     * Create a new context with debugging enabled/disabled.
     */
    public function withDebugInfo(bool $enableDebugInfo): self
    {
        return new self(
            format: $this->format,
            validationMode: $this->validationMode,
            unknownElementPolicy: $this->unknownElementPolicy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $this->skipNonEssentialValidation,
            enableDebugInfo: $enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: $this->customOptions
        );
    }

    /**
     * Create a new context with performance optimization enabled/disabled.
     */
    public function withPerformanceOptimization(bool $skipNonEssentialValidation): self
    {
        return new self(
            format: $this->format,
            validationMode: $this->validationMode,
            unknownElementPolicy: $this->unknownElementPolicy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $skipNonEssentialValidation,
            enableDebugInfo: $this->enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: $this->customOptions
        );
    }

    /**
     * Create a new context with additional custom options.
     *
     * @param array<string, mixed> $customOptions
     */
    public function withCustomOptions(array $customOptions): self
    {
        return new self(
            format: $this->format,
            validationMode: $this->validationMode,
            unknownElementPolicy: $this->unknownElementPolicy,
            includeExtensions: $this->includeExtensions,
            includeMetadata: $this->includeMetadata,
            skipNonEssentialValidation: $this->skipNonEssentialValidation,
            enableDebugInfo: $this->enableDebugInfo,
            validateReferences: $this->validateReferences,
            omitNullValues: $this->omitNullValues,
            omitEmptyArrays: $this->omitEmptyArrays,
            enableXmlNamespaces: $this->enableXmlNamespaces,
            enableXmlSchemaValidation: $this->enableXmlSchemaValidation,
            customOptions: array_merge($this->customOptions, $customOptions)
        );
    }

    /**
     * Check if the current format is JSON.
     */
    public function isJsonFormat(): bool
    {
        return $this->format === self::FORMAT_JSON;
    }

    /**
     * Check if the current format is XML.
     */
    public function isXmlFormat(): bool
    {
        return $this->format === self::FORMAT_XML;
    }

    /**
     * Check if strict validation is enabled.
     */
    public function isStrictValidation(): bool
    {
        return $this->validationMode === self::VALIDATION_STRICT;
    }

    /**
     * Check if lenient validation is enabled.
     */
    public function isLenientValidation(): bool
    {
        return $this->validationMode === self::VALIDATION_LENIENT;
    }

    /**
     * Check if unknown elements should be ignored.
     */
    public function shouldIgnoreUnknownElements(): bool
    {
        return $this->unknownElementPolicy === self::UNKNOWN_POLICY_IGNORE;
    }

    /**
     * Check if unknown elements should cause errors.
     */
    public function shouldErrorOnUnknownElements(): bool
    {
        return $this->unknownElementPolicy === self::UNKNOWN_POLICY_ERROR;
    }

    /**
     * Check if unknown elements should be preserved.
     */
    public function shouldPreserveUnknownElements(): bool
    {
        return $this->unknownElementPolicy === self::UNKNOWN_POLICY_PRESERVE;
    }

    /**
     * Get a custom option value.
     */
    public function getCustomOption(string $key, mixed $default = null): mixed
    {
        return $this->customOptions[$key] ?? $default;
    }

    /**
     * Convert the context to an array suitable for Symfony Serializer context.
     *
     * @return array<string, mixed>
     */
    public function toSymfonyContext(): array
    {
        return [
            'fhir_format' => $this->format,
            'fhir_validation_mode' => $this->validationMode,
            'fhir_unknown_element_policy' => $this->unknownElementPolicy,
            'fhir_include_extensions' => $this->includeExtensions,
            'fhir_include_metadata' => $this->includeMetadata,
            'fhir_skip_non_essential_validation' => $this->skipNonEssentialValidation,
            'fhir_enable_debug_info' => $this->enableDebugInfo,
            'fhir_validate_references' => $this->validateReferences,
            'fhir_omit_null_values' => $this->omitNullValues,
            'fhir_omit_empty_arrays' => $this->omitEmptyArrays,
            'fhir_enable_xml_namespaces' => $this->enableXmlNamespaces,
            'fhir_enable_xml_schema_validation' => $this->enableXmlSchemaValidation,
            'fhir_custom_options' => $this->customOptions,
            // Legacy context keys for backward compatibility
            'unknown_property_policy' => $this->unknownElementPolicy,
        ];
    }

    /**
     * Create a context from Symfony Serializer context array.
     *
     * @param array<string, mixed> $context
     */
    public static function fromSymfonyContext(array $context): self
    {
        return new self(
            format: $context['fhir_format'] ?? self::FORMAT_JSON,
            validationMode: $context['fhir_validation_mode'] ?? self::VALIDATION_STRICT,
            unknownElementPolicy: $context['fhir_unknown_element_policy'] ?? $context['unknown_property_policy'] ?? self::UNKNOWN_POLICY_IGNORE,
            includeExtensions: $context['fhir_include_extensions'] ?? true,
            includeMetadata: $context['fhir_include_metadata'] ?? true,
            skipNonEssentialValidation: $context['fhir_skip_non_essential_validation'] ?? false,
            enableDebugInfo: $context['fhir_enable_debug_info'] ?? false,
            validateReferences: $context['fhir_validate_references'] ?? false,
            omitNullValues: $context['fhir_omit_null_values'] ?? true,
            omitEmptyArrays: $context['fhir_omit_empty_arrays'] ?? true,
            enableXmlNamespaces: $context['fhir_enable_xml_namespaces'] ?? true,
            enableXmlSchemaValidation: $context['fhir_enable_xml_schema_validation'] ?? false,
            customOptions: $context['fhir_custom_options'] ?? []
        );
    }

    /**
     * Validate the configuration values.
     *
     * @throws \InvalidArgumentException If configuration is invalid
     */
    private function validateConfiguration(): void
    {
        $validFormats = [self::FORMAT_JSON, self::FORMAT_XML];
        if (!in_array($this->format, $validFormats, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid format "%s". Valid formats are: %s',
                $this->format,
                implode(', ', $validFormats)
            ));
        }

        $validValidationModes = [self::VALIDATION_STRICT, self::VALIDATION_LENIENT];
        if (!in_array($this->validationMode, $validValidationModes, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid validation mode "%s". Valid modes are: %s',
                $this->validationMode,
                implode(', ', $validValidationModes)
            ));
        }

        $validUnknownPolicies = [self::UNKNOWN_POLICY_IGNORE, self::UNKNOWN_POLICY_ERROR, self::UNKNOWN_POLICY_PRESERVE];
        if (!in_array($this->unknownElementPolicy, $validUnknownPolicies, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid unknown element policy "%s". Valid policies are: %s',
                $this->unknownElementPolicy,
                implode(', ', $validUnknownPolicies)
            ));
        }
    }
}