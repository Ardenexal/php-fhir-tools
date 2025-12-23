<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIROperationParameterUse
 * @description Code type wrapper for FHIROperationParameterUse enum
 */
class FHIRFHIROperationParameterUseType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIROperationParameterUse|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIROperationParameterUse|string|null $value = null,
	) {
	}
}
