<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceAssociationCodes
 * @description Code type wrapper for FHIRDeviceAssociationCodes enum
 */
class FHIRDeviceAssociationCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceAssociationCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceAssociationCodes|string|null $value = null,
	) {
	}
}
