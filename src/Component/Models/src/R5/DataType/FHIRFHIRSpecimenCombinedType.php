<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSpecimenCombined
 * @description Code type wrapper for FHIRSpecimenCombined enum
 */
class FHIRFHIRSpecimenCombinedType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSpecimenCombined|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSpecimenCombined|string|null $value = null,
	) {
	}
}
