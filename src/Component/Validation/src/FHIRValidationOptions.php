<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Settings for one {@see FHIRValidationServiceInterface::validateWithOptions()} call.
 *
 * Carries everything {@see FHIRValidationServiceInterface::validate()} takes, plus settings added
 * since. New settings go here rather than onto `validate()`, because a new parameter on an interface
 * method breaks every class that implements it.
 *
 * @author Ardenexal
 */
final readonly class FHIRValidationOptions
{
    /**
     * @param list<string>               $profileUrls             Profile canonical URLs to validate against (empty = base only)
     * @param bool                       $includeMustSupportInfo  Emit info-level violations for null/empty must-support properties
     * @param FHIRObligationContext|null $obligationContext       When set, population-class obligations matching the actor produce violations
     * @param bool                       $deriveProfilesFromClass Also evaluate every profile the resource's own class declares, inherited ones
     *                                                            included; for a profile resolved at runtime, also its constraint ancestors, on the
     *                                                            top-level resource only, as a generated class's inherited profiles are
     * @param bool                       $profilesFromMeta        Also validate each resource, nested ones included, against the profiles its own
     *                                                            `meta.profile` names, as the reference validator does. A profile the
     *                                                            resource's class declares is checked from the class; any other needs a
     *                                                            runtime profile resolver on the service, and without one is reported as
     *                                                            not checked
     */
    public function __construct(
        public array $profileUrls = [],
        public bool $includeMustSupportInfo = false,
        public ?FHIRObligationContext $obligationContext = null,
        public bool $deriveProfilesFromClass = false,
        public bool $profilesFromMeta = false,
    ) {
    }
}
