<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: MeasureReportType
 * URL: http://hl7.org/fhir/ValueSet/measure-report-type
 * Version: 4.0.1
 * Description: The type of the measure report.
 */
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
