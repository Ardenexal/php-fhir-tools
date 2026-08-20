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
    /**
     * @param bool $bestPractice Whether the source constraint carries
     *                           `elementdefinition-bestpractice`. Best-practice constraints express
     *                           a recommendation rather than a conformance rule — `dom-6` ("a
     *                           resource should have narrative") is the common one — and the HL7
     *                           Java reference validator does not report them by default. Reporting
     *                           them unconditionally buries real findings: 475 of our 767 R4 warnings
     *                           were `dom-6` alone. Only `dom-6` and `con-3` carry it in R4, across
     *                           189 declarations.
     */
    public function __construct(
        public readonly string $key,
        public readonly string $severity,
        public readonly string $expression,
        public readonly string $human,
        ?array $groups = null,
        mixed $payload = null,
        public readonly bool $bestPractice = false,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
