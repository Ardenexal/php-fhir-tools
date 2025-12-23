<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRDeviceUseStatementStatus
 * @description Code type wrapper for FHIRDeviceUseStatementStatus enum
 */
class FHIRFHIRDeviceUseStatementStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceUseStatementStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceUseStatementStatus|string|null $value = null,
	) {
	}
}
