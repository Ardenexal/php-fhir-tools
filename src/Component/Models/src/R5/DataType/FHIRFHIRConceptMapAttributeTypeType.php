<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConceptMapAttributeType
 * @description Code type wrapper for FHIRConceptMapAttributeType enum
 */
class FHIRFHIRConceptMapAttributeTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapAttributeType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapAttributeType|string|null $value = null,
	) {
	}
}
