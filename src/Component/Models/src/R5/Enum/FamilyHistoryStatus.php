<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Family History Status
 * URL: http://hl7.org/fhir/ValueSet/history-status
 * Version: 5.0.0
 * Description: A code that identifies the status of the family history record.
 */
enum FamilyHistoryStatus: string
{
    /** Partial */
    case partial = 'partial';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Health Unknown */
    case healthunknown = 'health-unknown';
}
