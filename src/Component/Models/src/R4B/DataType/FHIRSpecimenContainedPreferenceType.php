<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSpecimenContainedPreference
 * @description Code type wrapper for FHIRSpecimenContainedPreference enum
 */
class FHIRSpecimenContainedPreferenceType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSpecimenContainedPreference|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSpecimenContainedPreference|string|null $value = null,
	) {
	}
}
