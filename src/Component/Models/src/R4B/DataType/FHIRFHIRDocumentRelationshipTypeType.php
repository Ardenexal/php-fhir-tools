<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDocumentRelationshipType
 * @description Code type wrapper for FHIRDocumentRelationshipType enum
 */
class FHIRFHIRDocumentRelationshipTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentRelationshipType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentRelationshipType|string|null $value = null,
	) {
	}
}
