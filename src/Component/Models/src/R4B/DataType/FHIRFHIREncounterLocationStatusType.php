<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIREncounterLocationStatus
 * @description Code type wrapper for FHIREncounterLocationStatus enum
 */
class FHIRFHIREncounterLocationStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterLocationStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterLocationStatus|string|null $value = null,
	) {
	}
}
