<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ReportRelationshipType
 * URL: http://hl7.org/fhir/ValueSet/report-relation-type
 * Version: 4.3.0
 * Description: The type of relationship between reports.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/report-relation-type', version: '4.3.0')]
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
