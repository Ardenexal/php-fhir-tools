<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRStructureDefinitionKind
 * @description Code type wrapper for FHIRStructureDefinitionKind enum
 */
class FHIRFHIRStructureDefinitionKindType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureDefinitionKind|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureDefinitionKind|string|null $value = null,
	) {
	}
}
