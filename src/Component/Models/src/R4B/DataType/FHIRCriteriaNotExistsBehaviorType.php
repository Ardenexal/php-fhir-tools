<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCriteriaNotExistsBehavior
 * @description Code type wrapper for FHIRCriteriaNotExistsBehavior enum
 */
class FHIRCriteriaNotExistsBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCriteriaNotExistsBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCriteriaNotExistsBehavior|string|null $value = null,
	) {
	}
}
