<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element DocumentReference.content.profile
 * @description An identifier of the document constraints, encoding, structure, and template that the document conforms to beyond the base format indicated in the mimeType.
 */
class FHIRDocumentReferenceContentProfile extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical valueX Code|uri|canonical */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
