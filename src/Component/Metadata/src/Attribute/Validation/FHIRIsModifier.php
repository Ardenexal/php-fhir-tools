<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Marks a model property or constructor parameter as a modifier element in the FHIR sense.
 *
 * Present when the corresponding StructureDefinition element declares isModifier=true.
 * This is an informational marker only — no Symfony Validator is attached. Consuming code
 * discovers modifier properties via ReflectionProperty::getAttributes(FHIRIsModifier::class).
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
final class FHIRIsModifier
{
    public function __construct(
        public readonly ?string $reason = null,
    ) {
    }
}
