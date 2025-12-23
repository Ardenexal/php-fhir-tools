<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAdministrativeGender
 * @description Code type wrapper for FHIRAdministrativeGender enum
 */
class FHIRFHIRAdministrativeGenderType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdministrativeGender|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdministrativeGender|string|null $value = null,
	) {
	}
}
