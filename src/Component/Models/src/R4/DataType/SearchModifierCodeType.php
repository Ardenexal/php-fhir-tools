<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type SearchModifierCode
 * @description Code type wrapper for SearchModifierCode enum
 */
class SearchModifierCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\SearchModifierCode|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
