<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRCarePlanActivityStatus
 * @description Code type wrapper for FHIRCarePlanActivityStatus enum
 */
class FHIRFHIRCarePlanActivityStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityStatus|string|null $value = null,
	) {
	}
}
