<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Wraps a PHP scalar value with its canonical FHIR type name.
 *
 * FHIR resource properties declared as PHP scalars (bool, int, float, string)
 * lose their FHIR type context when accessed via property navigation. This
 * wrapper preserves that context so that type() and is() can distinguish
 * FHIR.boolean (from Patient.active) from System.Boolean (the FHIRPath literal).
 *
 * Consumers that need the underlying PHP scalar call normalizeValue() or access
 * the public $value property directly. Functions that need the FHIR type use
 * FHIRTypeResolver::inferType() which checks for this wrapper.
 */
final class FHIRTypedScalar
{
    /**
     * @param bool|int|float|string $value    The PHP scalar value
     * @param string                $fhirType The canonical FHIR type name (e.g. 'boolean', 'integer', 'decimal', 'string')
     */
    public function __construct(
        public readonly bool|int|float|string $value,
        public readonly string $fhirType,
    ) {
    }
}
