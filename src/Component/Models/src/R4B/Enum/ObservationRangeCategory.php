<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ObservationRangeCategory
 * URL: http://hl7.org/fhir/ValueSet/observation-range-category
 * Version: 4.3.0
 * Description: Codes identifying the category of observation range.
 */
enum ObservationRangeCategory: string
{
	/** reference range */
	case referencerange = 'reference';

	/** critical range */
	case criticalrange = 'critical';

	/** absolute range */
	case absoluterange = 'absolute';
}
