<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element DocumentReference.relatesTo
 * @description Relationships that this document has with other document references that already exist.
 */
class FHIRDocumentReferenceRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDocumentRelationshipTypeType code replaces | transforms | signs | appends */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDocumentRelationshipTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference target Target of the relationship */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $target = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
