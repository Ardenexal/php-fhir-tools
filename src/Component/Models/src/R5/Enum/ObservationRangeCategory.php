<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Observation Range Category
 * URL: http://hl7.org/fhir/ValueSet/observation-range-category
 * Version: 5.0.0
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
