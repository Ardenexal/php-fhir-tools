<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Immunization.education
 * @description Educational material presented to the patient (or guardian) at the time of vaccine administration.
 */
class FHIRImmunizationEducation extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string documentType Educational material document identifier */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $documentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri reference Educational material reference pointer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime publicationDate Educational material publication date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $publicationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime presentationDate Educational material presentation date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $presentationDate = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
