<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AdverseEventOutcome
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-outcome
 * Version: 4.0.1
 * Description: TODO (and should this be required?).
 */
enum AdverseEventOutcome: string
{
    /** Resolved */
    case resolved = 'resolved';

    /** Recovering */
    case recovering = 'recovering';

    /** Ongoing */
    case ongoing = 'ongoing';

    /** Resolved with Sequelae */
    case resolvedwithsequelae = 'resolvedWithSequelae';

    /** Fatal */
    case fatal = 'fatal';

    /** Unknown */
    case unknown = 'unknown';
}
