<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type StructureMapContextType
 * @description Code type wrapper for StructureMapContextType enum
 */
class StructureMapContextTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureMapContextType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
