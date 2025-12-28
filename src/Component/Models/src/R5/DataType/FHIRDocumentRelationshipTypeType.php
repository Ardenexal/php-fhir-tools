<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDocumentRelationshipType
 * @description Code type wrapper for FHIRDocumentRelationshipType enum
 */
class FHIRDocumentRelationshipTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentRelationshipType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentRelationshipType|string|null $value = null,
	) {
	}
}
