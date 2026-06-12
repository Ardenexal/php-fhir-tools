<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a property whose value is fixed by the profile (StructureDefinition element.fixed[x])
 * and must equal the declared value exactly.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRFixedValue extends Constraint
{
    public function __construct(
        public readonly mixed $value,
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
