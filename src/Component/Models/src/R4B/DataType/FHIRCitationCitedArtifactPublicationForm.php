<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.publicationForm
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
class FHIRCitationCitedArtifactPublicationForm extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPublishedIn publishedIn The collection the cited article or artifact is published in */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPublishedIn $publishedIn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPeriodicRelease periodicRelease The specific issue in which the cited article resides */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPeriodicRelease $periodicRelease = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime articleDate The date the article was added to the database, or the date the article was released */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $articleDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime lastRevisionDate The date the article was last revised or updated in the database */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $lastRevisionDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> language Language in which this form of the article is published */
		public array $language = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string accessionNumber Entry number or identifier for inclusion in a database */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $accessionNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string pageString Used for full display of pagination */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $pageString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string firstPage Used for isolated representation of first page */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $firstPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string lastPage Used for isolated representation of last page */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $lastPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string pageCount Number of pages or screens */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $pageCount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown copyright Copyright notice for the full article or artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $copyright = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
