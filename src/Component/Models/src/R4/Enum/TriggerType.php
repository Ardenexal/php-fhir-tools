<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TriggerType
 * URL: http://hl7.org/fhir/ValueSet/trigger-type
 * Version: 4.0.1
 * Description: The type of trigger.
 */
enum TriggerType: string
{
    /** Named Event */
    case namedevent = 'named-event';

    /** Periodic */
    case periodic = 'periodic';

    /** Data Changed */
    case datachanged = 'data-changed';

    /** Data Accessed */
    case dataaccessed = 'data-accessed';

    /** Data Access Ended */
    case dataaccessended = 'data-access-ended';
}
