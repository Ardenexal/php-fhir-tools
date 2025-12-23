<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCodeSystemHierarchyMeaning
 * @description Code type wrapper for FHIRCodeSystemHierarchyMeaning enum
 */
class FHIRFHIRCodeSystemHierarchyMeaningType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCodeSystemHierarchyMeaning|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCodeSystemHierarchyMeaning|string|null $value = null,
	) {
	}
}
