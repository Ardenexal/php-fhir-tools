<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRConceptMapGroupUnmappedMode
 * @description Code type wrapper for FHIRConceptMapGroupUnmappedMode enum
 */
class FHIRFHIRConceptMapGroupUnmappedModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConceptMapGroupUnmappedMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConceptMapGroupUnmappedMode|string|null $value = null,
	) {
	}
}
