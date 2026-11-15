<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AuditEventAction
 * URL: http://hl7.org/fhir/ValueSet/audit-event-action
 * Version: 4.3.0
 * Description: Indicator for type of action performed during the event that generated the event.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/audit-event-action', version: '4.3.0')]
enum AuditEventAction: string
{
    /** Create */
    case create = 'C';

    /** Read/View/Print */
    case readviewprint = 'R';

    /** Update */
    case update = 'U';

    /** Delete */
    case delete = 'D';

    /** Execute */
    case execute = 'E';
}
