<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ReportRelationshipType
 * URL: http://hl7.org/fhir/ValueSet/report-relation-type
 * Version: 4.3.0
 * Description: The type of relationship between reports.
 */
enum ReportRelationshipType: string
{
    /** Replaces */
    case replaces = 'replaces';

    /** Amends */
    case amends = 'amends';

    /** Appends */
    case appends = 'appends';

    /** Transforms */
    case transforms = 'transforms';

    /** Replaced With */
    case replacedwith = 'replacedWith';

    /** Amended With */
    case amendedwith = 'amendedWith';

    /** Appended With */
    case appendedwith = 'appendedWith';

    /** Transformed With */
    case transformedwith = 'transformedWith';
}
