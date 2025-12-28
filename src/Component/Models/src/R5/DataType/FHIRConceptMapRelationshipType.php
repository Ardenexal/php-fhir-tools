<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConceptMapRelationship
 * @description Code type wrapper for FHIRConceptMapRelationship enum
 */
class FHIRConceptMapRelationshipType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConceptMapRelationship|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConceptMapRelationship|string|null $value = null,
	) {
	}
}
