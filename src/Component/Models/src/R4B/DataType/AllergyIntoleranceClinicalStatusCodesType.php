<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type AllergyIntoleranceClinicalStatusCodes
 * @description Code type wrapper for AllergyIntoleranceClinicalStatusCodes enum
 */
class AllergyIntoleranceClinicalStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\AllergyIntoleranceClinicalStatusCodes|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
