<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRActionConditionKind
 * @description Code type wrapper for FHIRActionConditionKind enum
 */
class FHIRActionConditionKindType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRActionConditionKind|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
