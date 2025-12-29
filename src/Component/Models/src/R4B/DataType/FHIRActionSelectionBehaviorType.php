<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRActionSelectionBehavior
 * @description Code type wrapper for FHIRActionSelectionBehavior enum
 */
class FHIRActionSelectionBehaviorType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRActionSelectionBehavior|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
