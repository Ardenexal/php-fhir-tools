<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Immutable value object describing one concrete variant within a FHIR choice element.
 *
 * Choice elements (value[x], deceased[x]) are polymorphic: the concrete FHIR element name
 * encodes the runtime type. Each variant maps a PHP type to its FHIR element name and
 * semantic kind, enabling fast dispatch without reflection or class_exists() calls.
 *
 * @author Ardenexal
 */
final class PropertyVariantMetadata
{
    /**
     * @param string $fhirType    FHIR type code for this variant (e.g. 'boolean', 'dateTime')
     * @param string $propertyKind Semantic kind ('scalar', 'primitive', 'complex', etc.)
     * @param string $phpType     FQCN for class types; 'bool'/'int'/'float'/'string' for builtins
     * @param string $jsonKey     Concrete FHIR element name used in JSON/XML (e.g. 'deceasedBoolean')
     * @param bool   $isBuiltin   Pre-computed: true when phpType is a PHP builtin scalar
     */
    public function __construct(
        public readonly string $fhirType,
        public readonly string $propertyKind,
        public readonly string $phpType,
        public readonly string $jsonKey,
        public readonly bool $isBuiltin,
    ) {
    }

    /**
     * Compute isBuiltin from a phpType string.
     *
     * Avoids checking at call sites — centralises the logic so PropertyMetadataProvider
     * can construct instances without knowing the builtin set.
     */
    public static function fromArray(string $fhirType, string $propertyKind, string $phpType, string $jsonKey): self
    {
        $isBuiltin = in_array($phpType, ['bool', 'int', 'float', 'string'], true);

        return new self($fhirType, $propertyKind, $phpType, $jsonKey, $isBuiltin);
    }
}
