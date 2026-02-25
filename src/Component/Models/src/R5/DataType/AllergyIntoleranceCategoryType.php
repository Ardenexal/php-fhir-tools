<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type AllergyIntoleranceCategory
 * @description Code type wrapper for AllergyIntoleranceCategory enum
 */
class AllergyIntoleranceCategoryType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\AllergyIntoleranceCategory|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
