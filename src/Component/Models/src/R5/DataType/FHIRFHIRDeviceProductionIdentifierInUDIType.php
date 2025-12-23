<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceProductionIdentifierInUDI
 * @description Code type wrapper for FHIRDeviceProductionIdentifierInUDI enum
 */
class FHIRFHIRDeviceProductionIdentifierInUDIType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceProductionIdentifierInUDI|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceProductionIdentifierInUDI|string|null $value = null,
	) {
	}
}
