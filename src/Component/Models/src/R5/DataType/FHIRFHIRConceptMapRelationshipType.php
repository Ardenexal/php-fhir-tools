<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConceptMapRelationship
 * @description Code type wrapper for FHIRConceptMapRelationship enum
 */
class FHIRFHIRConceptMapRelationshipType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapRelationship|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapRelationship|string|null $value = null,
	) {
	}
}
