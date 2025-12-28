<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIREnableWhenBehavior
 * @description Code type wrapper for FHIREnableWhenBehavior enum
 */
class FHIREnableWhenBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREnableWhenBehavior|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREnableWhenBehavior|string|null $value = null,
	) {
	}
}
