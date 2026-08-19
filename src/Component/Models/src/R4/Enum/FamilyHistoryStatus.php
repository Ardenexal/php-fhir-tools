<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: FamilyHistoryStatus
 * URL: http://hl7.org/fhir/ValueSet/history-status
 * Version: 4.0.1
 * Description: A code that identifies the status of the family history record.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/history-status', version: '4.0.1')]
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
