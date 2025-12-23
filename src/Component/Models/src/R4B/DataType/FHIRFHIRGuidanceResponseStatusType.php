<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRGuidanceResponseStatus
 * @description Code type wrapper for FHIRGuidanceResponseStatus enum
 */
class FHIRFHIRGuidanceResponseStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuidanceResponseStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuidanceResponseStatus|string|null $value = null,
	) {
	}
}
