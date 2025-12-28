<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRRestfulCapabilityMode
 * @description Code type wrapper for FHIRRestfulCapabilityMode enum
 */
class FHIRRestfulCapabilityModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRestfulCapabilityMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRestfulCapabilityMode|string|null $value = null,
	) {
	}
}
