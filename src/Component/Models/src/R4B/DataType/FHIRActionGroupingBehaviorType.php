<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRActionGroupingBehavior
 * @description Code type wrapper for FHIRActionGroupingBehavior enum
 */
class FHIRActionGroupingBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionGroupingBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionGroupingBehavior|string|null $value = null,
	) {
	}
}
