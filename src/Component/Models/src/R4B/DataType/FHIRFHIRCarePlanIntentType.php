<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCarePlanIntent
 * @description Code type wrapper for FHIRCarePlanIntent enum
 */
class FHIRFHIRCarePlanIntentType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanIntent|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanIntent|string|null $value = null,
	) {
	}
}
