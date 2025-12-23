<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRRestfulCapabilityMode
 * @description Code type wrapper for FHIRRestfulCapabilityMode enum
 */
class FHIRFHIRRestfulCapabilityModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRestfulCapabilityMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRestfulCapabilityMode|string|null $value = null,
	) {
	}
}
