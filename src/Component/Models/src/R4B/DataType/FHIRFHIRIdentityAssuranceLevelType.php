<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRIdentityAssuranceLevel
 * @description Code type wrapper for FHIRIdentityAssuranceLevel enum
 */
class FHIRFHIRIdentityAssuranceLevelType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIdentityAssuranceLevel|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIdentityAssuranceLevel|string|null $value = null,
	) {
	}
}
