<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Contract.contentDefinition
 * @description Precusory content developed with a focus and intent of supporting the formation a Contract instance, which may be associated with and transformable into a Contract.
 */
class FHIRContractContentDefinition extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Content structure and use */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept subType Detailed Content Type Definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference publisher Publisher Entity */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $publisher = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime publicationDate When published */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $publicationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractResourcePublicationStatusCodesType publicationStatus amended | appended | cancelled | disputed | entered-in-error | executable + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractResourcePublicationStatusCodesType $publicationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Publication Ownership */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $copyright = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
