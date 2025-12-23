<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRClinicalImpressionStatus
 * @description Code type wrapper for FHIRClinicalImpressionStatus enum
 */
class FHIRFHIRClinicalImpressionStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRClinicalImpressionStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRClinicalImpressionStatus|string|null $value = null,
	) {
	}
}
