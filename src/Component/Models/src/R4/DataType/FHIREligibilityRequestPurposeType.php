<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIREligibilityRequestPurpose
 * @description Code type wrapper for FHIREligibilityRequestPurpose enum
 */
class FHIREligibilityRequestPurposeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREligibilityRequestPurpose|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREligibilityRequestPurpose|string|null $value = null,
	) {
	}
}
