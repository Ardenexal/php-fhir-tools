<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Declares the data types a choice element may hold under a profile (`value[x] only Quantity`).
 *
 * A profile narrows a choice element by listing fewer types than the base definition. The model
 * keeps one polymorphic property for every variant, so the narrowing has to be checked at
 * validation time: the value's FHIR type must be one of $types. The profile compiler emits this
 * inside a {@see FHIRProfileConstraint} or as a slice rule, at the choice element's path.
 *
 * Validated by `FHIRAllowedTypesValidator` in the Validation component.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRAllowedTypes extends Constraint
{
    /**
     * @param list<string> $types FHIR type codes the element may hold, as in `ElementDefinition.type.code`
     */
    public function __construct(
        public readonly array $types,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
