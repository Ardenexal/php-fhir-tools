<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference;

/**
 * @description The document and format referenced. There may be multiple content element repetitions, each with a different format.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content', fhirVersion: 'R4')]
class DocumentReferenceContent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment attachment Where to access the document */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $attachment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding format Format/content rules for the document */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $format = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
