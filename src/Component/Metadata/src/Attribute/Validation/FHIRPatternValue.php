<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a property with a FHIR pattern (StructureDefinition element.pattern[x]) whose key/value
 * pairs the instance value must contain as a subset.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRPatternValue extends Constraint
{
    /**
     * @param array<string, mixed> $pattern
     */
    public function __construct(
        public readonly array $pattern,
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
