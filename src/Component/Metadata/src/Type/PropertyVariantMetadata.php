<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Immutable value object describing one concrete variant within a FHIR choice element.
 *
 * Choice elements (value[x], deceased[x]) are polymorphic: the concrete FHIR element name
 * encodes the runtime type. Each variant maps a PHP type to its FHIR element name and
 * semantic kind, enabling fast dispatch without reflection or class_exists() calls.
 *
 * CDA polymorphism works the other way round: the element name is fixed and an `xsi:type` attribute
 * names the datatype, so those variants carry `typeName` and share the element name. Variant order is
 * load-bearing for both: a reader takes the first `instanceof` match, so the list must be ordered
 * subclass-before-superclass.
 *
 * @author Ardenexal
 */
final class PropertyVariantMetadata
{
    /**
     * @param string      $fhirType     FHIR type code for this variant (e.g. 'boolean', 'dateTime')
     * @param string      $propertyKind Semantic kind ('scalar', 'primitive', 'complex', etc.)
     * @param string      $phpType      FQCN for class types; 'bool'/'int'/'float'/'string' for builtins
     * @param string      $jsonKey      Concrete FHIR element name used in JSON/XML (e.g. 'deceasedBoolean')
     * @param bool        $isBuiltin    Pre-computed: true when phpType is a PHP builtin scalar
     * @param string|null $typeName     Published type name written as the XML type discriminator, for a
     *                                  'polymorphic' variant (CDA `xsi:type`). Null for FHIR 'choice' and
     *                                  'choiceGroup' variants, which encode the type in the element name
     *                                  instead and so have nothing to discriminate on.
     */
    public function __construct(
        public readonly string $fhirType,
        public readonly string $propertyKind,
        public readonly string $phpType,
        public readonly string $jsonKey,
        public readonly bool $isBuiltin,
        public readonly ?string $typeName = null,
    ) {
    }

    /**
     * Compute isBuiltin from a phpType string.
     *
     * Avoids checking at call sites — centralises the logic so PropertyMetadataProvider
     * can construct instances without knowing the builtin set.
     */
    public static function fromArray(
        string $fhirType,
        string $propertyKind,
        string $phpType,
        string $jsonKey,
        ?string $typeName = null,
    ): self {
        $isBuiltin = in_array($phpType, ['bool', 'int', 'float', 'string'], true);

        return new self($fhirType, $propertyKind, $phpType, $jsonKey, $isBuiltin, $typeName);
    }
}
