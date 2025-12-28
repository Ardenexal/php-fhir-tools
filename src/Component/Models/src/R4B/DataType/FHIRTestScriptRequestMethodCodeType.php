<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRTestScriptRequestMethodCode
 * @description Code type wrapper for FHIRTestScriptRequestMethodCode enum
 */
class FHIRTestScriptRequestMethodCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestScriptRequestMethodCode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestScriptRequestMethodCode|string|null $value = null,
	) {
	}
}
