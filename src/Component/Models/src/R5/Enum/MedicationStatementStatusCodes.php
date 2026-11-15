<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: MedicationStatement Status Codes
 * URL: http://hl7.org/fhir/ValueSet/medication-statement-status
 * Version: 5.0.0
 * Description: MedicationStatement Status Codes
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/medication-statement-status', version: '5.0.0')]
enum MedicationStatementStatusCodes: string
{
    /** Recorded */
    case recorded = 'recorded';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Draft */
    case draft = 'draft';
}
