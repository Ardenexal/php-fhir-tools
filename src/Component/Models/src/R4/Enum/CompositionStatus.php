<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CompositionStatus
 * URL: http://hl7.org/fhir/ValueSet/composition-status
 * Version: 4.0.1
 * Description: The workflow/clinical status of the composition.
 */
enum CompositionStatus: string
{
    /** Preliminary */
    case preliminary = 'preliminary';

    /** Final */
    case final = 'final';

    /** Amended */
    case amended = 'amended';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
