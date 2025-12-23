<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductCategory
 * @description Code type wrapper for FHIRBiologicallyDerivedProductCategory enum
 */
class FHIRFHIRBiologicallyDerivedProductCategoryType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductCategory|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBiologicallyDerivedProductCategory|string|null $value = null,
	) {
	}
}
