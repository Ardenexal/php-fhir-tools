<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIREvidenceVariableType
 * @description Code type wrapper for FHIREvidenceVariableType enum
 */
class FHIREvidenceVariableTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREvidenceVariableType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREvidenceVariableType|string|null $value = null,
	) {
	}
}
