<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Resolves the FHIR type name for a named property on a FHIR element object.
 * Used by FHIRValidationService to evaluate bare-type extension contexts
 * (e.g. #[FHIRExtensionContext(type: 'element', expression: 'HumanName')]).
 */
interface FHIRTypeHierarchyResolverInterface
{
    /**
     * Return the FHIR type name (e.g. 'HumanName', 'Identifier', 'date') for property
     * $propertyName on $element, or null if the type cannot be determined.
     *
     * Example: resolvePropertyType($patient, 'name') → 'HumanName'
     */
    public function resolvePropertyType(object $element, string $propertyName): ?string;
}
