<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductStatus
 * @description Code type wrapper for FHIRBiologicallyDerivedProductStatus enum
 */
class FHIRFHIRBiologicallyDerivedProductStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductStatus|string|null $value = null,
	) {
	}
}
