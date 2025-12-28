<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIREventCapabilityMode
 * @description Code type wrapper for FHIREventCapabilityMode enum
 */
class FHIREventCapabilityModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventCapabilityMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventCapabilityMode|string|null $value = null,
	) {
	}
}
