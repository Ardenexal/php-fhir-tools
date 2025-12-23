<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIREncounterStatus
 * @description Code type wrapper for FHIREncounterStatus enum
 */
class FHIRFHIREncounterStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterStatus|string|null $value = null,
	) {
	}
}
