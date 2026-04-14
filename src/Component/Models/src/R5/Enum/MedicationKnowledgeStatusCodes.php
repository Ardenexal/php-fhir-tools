<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: MedicationKnowledge Status Codes
 * URL: http://hl7.org/fhir/ValueSet/medicationknowledge-status
 * Version: 5.0.0
 * Description: MedicationKnowledge Status Codes
 */
enum MedicationKnowledgeStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Inactive */
    case inactive = 'inactive';
}
