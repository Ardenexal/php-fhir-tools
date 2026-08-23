<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: MeasureReportType
 * URL: http://hl7.org/fhir/ValueSet/measure-report-type
 * Version: 4.3.0
 * Description: The type of the measure report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/measure-report-type', version: '4.3.0')]
enum MeasureReportType: string
{
    /** Individual */
    case individual = 'individual';

    /** Subject List */
    case subjectlist = 'subject-list';

    /** Summary */
    case summary = 'summary';

    /** Data Collection */
    case datacollection = 'data-collection';
}
