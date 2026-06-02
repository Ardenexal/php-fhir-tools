<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Class-level metadata attribute declaring the slicing rules for one sliced array property.
 *
 * This is pure metadata — it does NOT extend Symfony's Constraint and has no validator.
 * The FHIRSliceConstraintValidator reads it via reflection alongside #[FHIRSliceConstraint]
 * attributes to determine whether items that match no defined slice should be rejected
 * (closed) or allowed (open / openAtEnd).
 *
 * One per sliced property per profile. Multiple properties on the same class each get
 * their own #[FHIRSlicingRules] attribute.
 *
 * openAtEnd: Extra (unmatched) items are allowed only after all matched-slice items.
 * The validator enforces that no matched-slice item appears after any unmatched item.
 *
 * Example:
 * <pre>
 * #[FHIRSlicingRules(
 *     property: 'identifier',
 *     rules: 'closed',
 *     groups: ['http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient'],
 * )]
 * class AUCorePatientProfile extends AUBasePatientProfile { ... }
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRSlicingRules
{
    /**
     * @param string       $property Property path on the target class (e.g. 'identifier')
     * @param string       $rules    Slicing rules: 'open', 'openAtEnd', or 'closed'
     * @param list<string> $groups   Profile URL groups under which these rules are active
     */
    public function __construct(
        public readonly string $property,
        public readonly string $rules,
        public readonly array $groups = [],
    ) {
    }
}
