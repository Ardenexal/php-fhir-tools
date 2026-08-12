<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CompositionStatus
 * URL: http://hl7.org/fhir/ValueSet/composition-status
 * Version: 4.3.0
 * Description: The workflow/clinical status of the composition.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/composition-status', version: '4.3.0')]
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
