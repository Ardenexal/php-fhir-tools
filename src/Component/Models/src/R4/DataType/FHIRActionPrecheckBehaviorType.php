<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRActionPrecheckBehavior
 * @description Code type wrapper for FHIRActionPrecheckBehavior enum
 */
class FHIRActionPrecheckBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionPrecheckBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionPrecheckBehavior|string|null $value = null,
	) {
	}
}
