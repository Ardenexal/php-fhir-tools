<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceAssociationCodes
 * @description Code type wrapper for FHIRDeviceAssociationCodes enum
 */
class FHIRFHIRDeviceAssociationCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceAssociationCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceAssociationCodes|string|null $value = null,
	) {
	}
}
