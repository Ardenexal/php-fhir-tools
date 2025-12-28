<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAdministrativeGender
 * @description Code type wrapper for FHIRAdministrativeGender enum
 */
class FHIRAdministrativeGenderType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdministrativeGender|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdministrativeGender|string|null $value = null,
	) {
	}
}
