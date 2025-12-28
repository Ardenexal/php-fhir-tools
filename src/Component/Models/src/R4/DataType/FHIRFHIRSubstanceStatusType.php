<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRFHIRSubstanceStatus
 * @description Code type wrapper for FHIRFHIRSubstanceStatus enum
 */
class FHIRFHIRSubstanceStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubstanceStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubstanceStatus|string|null $value = null,
	) {
	}
}
