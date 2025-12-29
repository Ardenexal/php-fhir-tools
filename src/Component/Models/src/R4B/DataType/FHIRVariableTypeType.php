<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRVariableType
 * @description Code type wrapper for FHIRVariableType enum
 */
class FHIRVariableTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRVariableType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
