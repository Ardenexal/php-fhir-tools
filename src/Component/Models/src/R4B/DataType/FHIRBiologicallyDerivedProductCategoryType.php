<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductCategory
 * @description Code type wrapper for FHIRBiologicallyDerivedProductCategory enum
 */
class FHIRBiologicallyDerivedProductCategoryType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBiologicallyDerivedProductCategory|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBiologicallyDerivedProductCategory|string|null $value = null,
	) {
	}
}
