<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRGuideParameterCode
 * @description Code type wrapper for FHIRGuideParameterCode enum
 */
class FHIRGuideParameterCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGuideParameterCode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGuideParameterCode|string|null $value = null,
	) {
	}
}
