<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Submit Data Update Type
 * URL: http://hl7.org/fhir/ValueSet/submit-data-update-type
 * Version: 5.0.0
 * Description: Concepts for how a measure report consumer and receiver coordinate data exchange updates. The choices are snapshot or incremental updates
 */
enum FHIRSubmitDataUpdateType: string
{
    /** Incremental */
    case incremental = 'incremental';

    /** Snapshot */
    case snapshot = 'snapshot';
}
