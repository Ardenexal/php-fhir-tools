<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCriteriaNotExistsBehavior
 * @description Code type wrapper for FHIRCriteriaNotExistsBehavior enum
 */
class FHIRFHIRCriteriaNotExistsBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCriteriaNotExistsBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCriteriaNotExistsBehavior|string|null $value = null,
	) {
	}
}
