<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: StructureMapModelMode
 * URL: http://hl7.org/fhir/ValueSet/map-model-mode
 * Version: 4.0.1
 * Description: How the referenced structure is used in this mapping.
 */
enum StructureMapModelMode: string
{
    /** Source Structure Definition */
    case sourcestructuredefinition = 'source';

    /** Queried Structure Definition */
    case queriedstructuredefinition = 'queried';

    /** Target Structure Definition */
    case targetstructuredefinition = 'target';

    /** Produced Structure Definition */
    case producedstructuredefinition = 'produced';
}
