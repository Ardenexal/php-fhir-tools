<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRActionPrecheckBehavior
 * @description Code type wrapper for FHIRActionPrecheckBehavior enum
 */
class FHIRFHIRActionPrecheckBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionPrecheckBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionPrecheckBehavior|string|null $value = null,
	) {
	}
}
