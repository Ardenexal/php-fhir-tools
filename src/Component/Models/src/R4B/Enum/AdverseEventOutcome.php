<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AdverseEventOutcome
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-outcome
 * Version: 4.3.0
 * Description: TODO (and should this be required?).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/adverse-event-outcome', version: '4.3.0')]
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
