<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.publicationForm.periodicRelease
 * @description The specific issue in which the cited article resides.
 */
class FHIRCitationCitedArtifactPublicationFormPeriodicRelease extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept citedMedium Internet or Print */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $citedMedium = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string volume Volume number of journal in which the article is published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $volume = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string issue Issue, part or supplement of journal in which the article is published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $issue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication dateOfPublication Defining the date on which the issue of the journal was published */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication $dateOfPublication = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
