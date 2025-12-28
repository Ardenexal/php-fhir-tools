<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Educational material presented to the patient (or guardian) at the time of vaccine administration.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.education', fhirVersion: 'R4')]
class FHIRImmunizationEducation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string documentType Educational material document identifier */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $documentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri reference Educational material reference pointer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime publicationDate Educational material publication date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $publicationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime presentationDate Educational material presentation date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $presentationDate = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
