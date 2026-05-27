<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Marks a model property or constructor parameter with a FHIR obligation requirement.
 *
 * Present when the corresponding StructureDefinition snapshot element carries an obligation
 * extension (http://hl7.org/fhir/StructureDefinition/obligation). This is a metadata marker —
 * no Symfony Validator is attached. Consuming code discovers obligations via:
 *   ReflectionParameter::getAttributes(FHIRObligation::class)
 *   ReflectionProperty::getAttributes(FHIRObligation::class)
 *
 * IS_REPEATABLE allows multiple #[FHIRObligation] instances on the same constructor parameter,
 * one per obligation defined on the StructureDefinition element.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
final class FHIRObligation
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $actor = null,
        public readonly ?string $filter = null,
        public readonly ?string $documentation = null,
    ) {
    }
}
