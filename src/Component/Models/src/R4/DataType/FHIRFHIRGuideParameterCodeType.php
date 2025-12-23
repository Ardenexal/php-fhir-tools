<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRGuideParameterCode
 * @description Code type wrapper for FHIRGuideParameterCode enum
 */
class FHIRFHIRGuideParameterCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuideParameterCode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuideParameterCode|string|null $value = null,
	) {
	}
}
