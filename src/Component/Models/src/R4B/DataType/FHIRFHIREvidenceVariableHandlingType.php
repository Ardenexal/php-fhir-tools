<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIREvidenceVariableHandling
 * @description Code type wrapper for FHIREvidenceVariableHandling enum
 */
class FHIRFHIREvidenceVariableHandlingType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIREvidenceVariableHandling|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIREvidenceVariableHandling|string|null $value = null,
	) {
	}
}
