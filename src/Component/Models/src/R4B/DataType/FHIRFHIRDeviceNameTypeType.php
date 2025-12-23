<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDeviceNameType
 * @description Code type wrapper for FHIRDeviceNameType enum
 */
class FHIRFHIRDeviceNameTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceNameType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceNameType|string|null $value = null,
	) {
	}
}
