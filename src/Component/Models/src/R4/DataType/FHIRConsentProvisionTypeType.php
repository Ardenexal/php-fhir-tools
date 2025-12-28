<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRConsentProvisionType
 * @description Code type wrapper for FHIRConsentProvisionType enum
 */
class FHIRConsentProvisionTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConsentProvisionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConsentProvisionType|string|null $value = null,
	) {
	}
}
