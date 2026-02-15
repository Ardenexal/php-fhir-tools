<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: StructureMapInputMode
 * URL: http://hl7.org/fhir/ValueSet/map-input-mode
 * Version: 4.0.1
 * Description: Mode for this instance of data.
 */
enum StructureMapInputMode: string
{
    /** Source Instance */
    case sourceinstance = 'source';

    /** Target Instance */
    case targetinstance = 'target';
}
