<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Imaging Selection Status
 * URL: http://hl7.org/fhir/ValueSet/imagingselection-status
 * Version: 5.0.0
 * Description: The status of the ImagingSelection.
 */
enum FHIRImagingSelectionStatus: string
{
    /** Available */
    case available = 'available';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
