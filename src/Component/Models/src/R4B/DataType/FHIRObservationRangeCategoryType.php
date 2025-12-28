<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRObservationRangeCategory
 * @description Code type wrapper for FHIRObservationRangeCategory enum
 */
class FHIRObservationRangeCategoryType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRObservationRangeCategory|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRObservationRangeCategory|string|null $value = null,
	) {
	}
}
