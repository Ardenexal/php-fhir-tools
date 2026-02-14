<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: LinkageType
 * URL: http://hl7.org/fhir/ValueSet/linkage-type
 * Version: 4.0.1
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
