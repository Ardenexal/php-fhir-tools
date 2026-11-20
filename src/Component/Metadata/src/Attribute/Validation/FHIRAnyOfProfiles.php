<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Declares that an element's value must conform to at least one of several profiles
 * (`Patient.address only (AddressA or AddressB)`).
 *
 * A profile that types an element with one data-type profile has that profile's rules compiled into
 * its own, but an element whose types name several profiles, or several profiled types, can be
 * satisfied by any one of them, which a flat list of rules cannot express. The value is instead checked against
 * each profile on the side. A value whose type is one of $openTypes is allowed without a profile.
 * The profile compiler emits this inside a {@see FHIRProfileConstraint} or as a slice rule.
 *
 * Validated by `FHIRAnyOfProfilesValidator` in the Validation component.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRAnyOfProfiles extends Constraint
{
    /**
     * @param list<string> $profiles  Canonical URLs of the profiles, as in `ElementDefinition.type.profile`, in order
     * @param list<string> $openTypes FHIR type codes the element also allows with no profile, as in `ElementDefinition.type.code`
     */
    public function __construct(
        public readonly array $profiles,
        public readonly array $openTypes = [],
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
