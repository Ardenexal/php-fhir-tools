<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Linkage Type
 * URL: http://hl7.org/fhir/ValueSet/linkage-type
 * Version: 5.0.0
 * Description: Used to distinguish different roles a resource can play within a set of linked resources.
 */
enum LinkageType: string
{
    /** Source of Truth */
    case sourceoftruth = 'source';

    /** Alternate Record */
    case alternaterecord = 'alternate';

    /** Historical/Obsolete Record */
    case historicalobsoleterecord = 'historical';
}
