<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Map Input Mode
 * URL: http://hl7.org/fhir/ValueSet/map-input-mode
 * Version: 5.0.0
 * Description: Mode for this instance of data.
 */
enum StructureMapInputMode: string
{
    /** Source Instance */
    case sourceinstance = 'source';

    /** Target Instance */
    case targetinstance = 'target';
}
