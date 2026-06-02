<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Declares that a Reference- or canonical-typed property must point to a resource
 * conforming to at least one of the listed profile URLs (OR semantics).
 *
 * Emitted by the generator when `element.type[].targetProfile` is non-empty.
 * Validation is performed by `FHIRTargetProfileValidator`, which delegates
 * reference resolution to `FHIRReferenceResolverInterface`. When no resolver is
 * configured (NullFHIRReferenceResolver), the constraint is silently skipped.
 *
 * Example:
 * <pre>
 * #[FHIRTargetProfile(targetProfiles: [
 *     'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient',
 *     'http://hl7.org.au/fhir/StructureDefinition/au-base-patient',
 * ])]
 * public ?Reference $subject = null,
 * </pre>
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRTargetProfile extends Constraint
{
    /**
     * @param list<string> $targetProfiles Canonical profile URLs; at least one must match (OR semantics)
     */
    public function __construct(
        public readonly array $targetProfiles,
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
