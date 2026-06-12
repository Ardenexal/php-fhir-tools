<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Declares a FHIR invariant (StructureDefinition element.constraint) as a FHIRPath expression
 * that must hold on instances of the annotated class.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRPathInvariant extends Constraint
{
    public function __construct(
        public readonly string $key,
        public readonly string $severity,
        public readonly string $expression,
        public readonly string $human,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
