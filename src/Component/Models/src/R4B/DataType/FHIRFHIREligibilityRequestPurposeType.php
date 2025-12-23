<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIREligibilityRequestPurpose
 * @description Code type wrapper for FHIREligibilityRequestPurpose enum
 */
class FHIRFHIREligibilityRequestPurposeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREligibilityRequestPurpose|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREligibilityRequestPurpose|string|null $value = null,
	) {
	}
}
