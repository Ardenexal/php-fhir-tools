<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRFamilyHistoryStatus
 * @description Code type wrapper for FHIRFamilyHistoryStatus enum
 */
class FHIRFamilyHistoryStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFamilyHistoryStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFamilyHistoryStatus|string|null $value = null,
	) {
	}
}
