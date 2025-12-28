<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceUsageStatus
 * @description Code type wrapper for FHIRDeviceUsageStatus enum
 */
class FHIRDeviceUsageStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceUsageStatus|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
