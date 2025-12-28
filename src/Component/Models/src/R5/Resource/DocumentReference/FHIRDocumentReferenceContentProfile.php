<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description An identifier of the document constraints, encoding, structure, and template that the document conforms to beyond the base format indicated in the mimeType.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content.profile', fhirVersion: 'R5')]
class FHIRDocumentReferenceContentProfile extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical valueX Code|uri|canonical */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
