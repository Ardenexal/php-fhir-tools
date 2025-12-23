<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRActionRequiredBehavior
 * @description Code type wrapper for FHIRActionRequiredBehavior enum
 */
class FHIRFHIRActionRequiredBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionRequiredBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionRequiredBehavior|string|null $value = null,
	) {
	}
}
