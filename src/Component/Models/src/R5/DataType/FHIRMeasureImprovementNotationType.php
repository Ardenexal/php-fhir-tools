<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRMeasureImprovementNotation
 * @description Code type wrapper for FHIRMeasureImprovementNotation enum
 */
class FHIRMeasureImprovementNotationType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMeasureImprovementNotation|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMeasureImprovementNotation|string|null $value = null,
	) {
	}
}
