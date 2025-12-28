<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceDispenseStatusCodes
 * @description Code type wrapper for FHIRDeviceDispenseStatusCodes enum
 */
class FHIRDeviceDispenseStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceDispenseStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceDispenseStatusCodes|string|null $value = null,
	) {
	}
}
