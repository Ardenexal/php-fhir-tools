<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AuditEventOutcome
 * URL: http://hl7.org/fhir/ValueSet/audit-event-outcome
 * Version: 4.0.1
 * Description: Indicates whether the event succeeded or failed.
 */
enum AuditEventOutcome: string
{
    /** Success */
    case success = '0';

    /** Minor failure */
    case minorfailure = '4';

    /** Serious failure */
    case seriousfailure = '8';

    /** Major failure */
    case majorfailure = '12';
}
