<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRActionSelectionBehavior
 * @description Code type wrapper for FHIRActionSelectionBehavior enum
 */
class FHIRActionSelectionBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionSelectionBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionSelectionBehavior|string|null $value = null,
	) {
	}
}
