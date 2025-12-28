<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRConditionalDeleteStatus
 * @description Code type wrapper for FHIRConditionalDeleteStatus enum
 */
class FHIRConditionalDeleteStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionalDeleteStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionalDeleteStatus|string|null $value = null,
	) {
	}
}
