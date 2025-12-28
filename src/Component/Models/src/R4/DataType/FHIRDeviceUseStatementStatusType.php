<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRDeviceUseStatementStatus
 * @description Code type wrapper for FHIRDeviceUseStatementStatus enum
 */
class FHIRDeviceUseStatementStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceUseStatementStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceUseStatementStatus|string|null $value = null,
	) {
	}
}
