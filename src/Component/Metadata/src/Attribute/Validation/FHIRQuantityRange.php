<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a property with FHIR Quantity range bounds from minValueQuantity / maxValueQuantity
 * StructureDefinition fields.
 *
 * Each bound is an array with 'value' (float), 'system' (URI string or null), and 'code'
 * (unit code or null). Comparison is only possible when both instance and bound share the
 * same system+code; cross-unit comparisons emit a fhir:warning instead of fhir:error.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRQuantityRange extends Constraint
{
    /**
     * @param array{value: float, system: ?string, code: ?string}|null $minValue
     * @param array{value: float, system: ?string, code: ?string}|null $maxValue
     */
    public function __construct(
        public readonly ?array $minValue,
        public readonly ?array $maxValue,
    ) {
        parent::__construct();
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
