<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Declares that a Reference- or canonical-typed property must point to a resource
 * conforming to at least one of the listed profile URLs (OR semantics).
 *
 * Emitted by the generator and the runtime profile compiler when `element.type[].targetProfile`
 * is non-empty. Validation is performed by `FHIRTargetProfileValidator`. It checks the type a
 * literal reference or canonical names without resolving anything, and delegates profile
 * conformance of the target to `FHIRReferenceResolverInterface`; with no resolver configured
 * (NullFHIRReferenceResolver), only that conformance check is skipped.
 *
 * An element whose Reference and canonical types list different targets, such as
 * `Reference(Group) | canonical(EvidenceVariable)`, carries one attribute per kind, each naming
 * the kind it applies to. An attribute with no kind applies to every value.
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
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
final class FHIRTargetProfile extends Constraint
{
    /** Applies to Reference and CodeableReference values only. */
    public const string KIND_REFERENCE = 'Reference';

    /** Applies to canonical values only. */
    public const string KIND_CANONICAL = 'canonical';

    /**
     * @param list<string> $targetProfiles Canonical profile URLs; at least one must match (OR semantics)
     * @param string|null  $kind           self::KIND_REFERENCE or self::KIND_CANONICAL to apply to that kind of value only; null for every value
     *
     * @throws \InvalidArgumentException When $kind is any other string
     */
    public function __construct(
        public readonly array $targetProfiles,
        ?array $groups = null,
        mixed $payload = null,
        public readonly ?string $kind = null,
    ) {
        if ($kind !== null && $kind !== self::KIND_REFERENCE && $kind !== self::KIND_CANONICAL) {
            throw new \InvalidArgumentException(sprintf('FHIRTargetProfile kind must be "%s", "%s" or null; got "%s".', self::KIND_REFERENCE, self::KIND_CANONICAL, $kind));
        }

        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
