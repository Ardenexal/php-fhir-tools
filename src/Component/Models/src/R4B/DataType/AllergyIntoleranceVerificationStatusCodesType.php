<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type AllergyIntoleranceVerificationStatusCodes
 * @description Code type wrapper for AllergyIntoleranceVerificationStatusCodes enum
 */
class AllergyIntoleranceVerificationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4B\Enum\AllergyIntoleranceVerificationStatusCodes|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
