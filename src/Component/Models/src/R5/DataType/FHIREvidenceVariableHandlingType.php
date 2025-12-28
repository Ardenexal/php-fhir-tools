<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIREvidenceVariableHandling
 * @description Code type wrapper for FHIREvidenceVariableHandling enum
 */
class FHIREvidenceVariableHandlingType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIREvidenceVariableHandling|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIREvidenceVariableHandling|string|null $value = null,
	) {
	}
}
