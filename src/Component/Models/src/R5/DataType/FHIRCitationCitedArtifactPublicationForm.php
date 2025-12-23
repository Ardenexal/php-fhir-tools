<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.publicationForm
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
class FHIRCitationCitedArtifactPublicationForm extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCitationCitedArtifactPublicationFormPublishedIn publishedIn The collection the cited article or artifact is published in */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCitationCitedArtifactPublicationFormPublishedIn $publishedIn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept citedMedium Internet or Print */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $citedMedium = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string volume Volume number of journal or other collection in which the article is published */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $volume = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string issue Issue, part or supplement of journal or other collection in which the article is published */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $issue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime articleDate The date the article was added to the database, or the date the article was released */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $articleDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publicationDateText Text representation of the date on which the issue of the cited artifact was published */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $publicationDateText = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publicationDateSeason Season in which the cited artifact was published */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $publicationDateSeason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime lastRevisionDate The date the article was last revised or updated in the database */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $lastRevisionDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> language Language(s) in which this form of the article is published */
		public array $language = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string accessionNumber Entry number or identifier for inclusion in a database */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $accessionNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string pageString Used for full display of pagination */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $pageString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string firstPage Used for isolated representation of first page */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $firstPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string lastPage Used for isolated representation of last page */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $lastPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string pageCount Number of pages or screens */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $pageCount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Copyright notice for the full article or artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $copyright = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
