<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRCarePlanActivityKind
 * @description Code type wrapper for FHIRCarePlanActivityKind enum
 */
class FHIRFHIRCarePlanActivityKindType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityKind|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCarePlanActivityKind|string|null $value = null,
	) {
	}
}
