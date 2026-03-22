<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Condition Clinical Status Codes
 * URL: http://hl7.org/fhir/ValueSet/condition-clinical
 * Version: 4.0.1
 * Description: Preferred value set for Condition Clinical Status.
 */
enum ConditionClinicalStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';
}
