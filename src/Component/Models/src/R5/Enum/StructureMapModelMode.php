<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Structure Map Model Mode
 * URL: http://hl7.org/fhir/ValueSet/map-model-mode
 * Version: 5.0.0
 * Description: How the referenced structure is used in this mapping.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/map-model-mode', version: '5.0.0')]
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
