<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRClinicalUseDefinitionType
 * @description Code type wrapper for FHIRClinicalUseDefinitionType enum
 */
class FHIRClinicalUseDefinitionTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRClinicalUseDefinitionType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRClinicalUseDefinitionType|string|null $value = null,
	) {
	}
}
