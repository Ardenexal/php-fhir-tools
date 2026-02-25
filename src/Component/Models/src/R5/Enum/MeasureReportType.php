<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Measure Report Type
 * URL: http://hl7.org/fhir/ValueSet/measure-report-type
 * Version: 5.0.0
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

	/** Data Exchange */
	case dataexchange = 'data-exchange';
}
