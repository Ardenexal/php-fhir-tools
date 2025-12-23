<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIREvidenceVariableType
 * @description Code type wrapper for FHIREvidenceVariableType enum
 */
class FHIRFHIREvidenceVariableTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREvidenceVariableType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREvidenceVariableType|string|null $value = null,
	) {
	}
}
