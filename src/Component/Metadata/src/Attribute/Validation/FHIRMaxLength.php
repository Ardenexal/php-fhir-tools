<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a primitive property whose value a profile limits to at most $max characters
 * (StructureDefinition element.maxLength).
 *
 * Example: AU Base's Medicare card number limits `Identifier.value` to 11 characters.
 * Validation is performed by `FHIRMaxLengthValidator`.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRMaxLength extends Constraint
{
    /**
     * @param int $max The most characters the value may hold
     */
    public function __construct(
        public readonly int $max,
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
