<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRMeasureImprovementNotation
 * @description Code type wrapper for FHIRMeasureImprovementNotation enum
 */
class FHIRFHIRMeasureImprovementNotationType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureImprovementNotation|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureImprovementNotation|string|null $value = null,
	) {
	}
}
