<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ListMode
 * URL: http://hl7.org/fhir/ValueSet/list-mode
 * Version: 4.0.1
 * Description: The processing mode that applies to this list.
 */
enum ListMode: string
{
    /** Working List */
    case workinglist = 'working';

    /** Snapshot List */
    case snapshotlist = 'snapshot';

    /** Change List */
    case changelist = 'changes';
}
