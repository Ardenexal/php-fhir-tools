<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Condition Clinical Status Codes
 * URL: http://hl7.org/fhir/ValueSet/condition-clinical
 * Version: 5.0.0
 * Description: Preferred value set for Condition Clinical Status.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/condition-clinical', version: '5.0.0')]
enum ConditionClinicalStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Recurrence */
    case recurrence = 'recurrence';

    /** Relapse */
    case relapse = 'relapse';

    /** Inactive */
    case inactive = 'inactive';

    /** Remission */
    case remission = 'remission';

    /** Resolved */
    case resolved = 'resolved';

    /** Unknown */
    case unknown = 'unknown';
}
