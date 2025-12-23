<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.relatesTo
 * @description The artifact related to the cited artifact.
 */
class FHIRCitationCitedArtifactRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifactTypeExpandedType type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as | reprint | reprint-of */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifactTypeExpandedType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> classifier Additional classifiers */
		public array $classifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string label Short label */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $label = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string display Brief description of the related artifact */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown citation Bibliographic citation for the artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $citation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment document What document is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment $document = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical resource What artifact is being referenced */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference resourceReference What artifact, if not a conformance resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $resourceReference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
