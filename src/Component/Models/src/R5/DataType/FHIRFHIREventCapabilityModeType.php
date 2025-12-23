<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIREventCapabilityMode
 * @description Code type wrapper for FHIREventCapabilityMode enum
 */
class FHIRFHIREventCapabilityModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREventCapabilityMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREventCapabilityMode|string|null $value = null,
	) {
	}
}
