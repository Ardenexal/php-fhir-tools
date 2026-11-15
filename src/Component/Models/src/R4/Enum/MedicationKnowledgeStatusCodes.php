<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Medication knowledge  status  codes
 * URL: http://hl7.org/fhir/ValueSet/medicationknowledge-status
 * Version: 4.0.1
 * Description: MedicationKnowledge Status Codes
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/medicationknowledge-status', version: '4.0.1')]
enum MedicationKnowledgeStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
