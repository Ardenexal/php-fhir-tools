<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactPublicationForm extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPublishedIn publishedIn The collection the cited article or artifact is published in */
		public ?FHIRCitationCitedArtifactPublicationFormPublishedIn $publishedIn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationFormPeriodicRelease periodicRelease The specific issue in which the cited article resides */
		public ?FHIRCitationCitedArtifactPublicationFormPeriodicRelease $periodicRelease = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime articleDate The date the article was added to the database, or the date the article was released */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $articleDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime lastRevisionDate The date the article was last revised or updated in the database */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $lastRevisionDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> language Language in which this form of the article is published */
		public array $language = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string accessionNumber Entry number or identifier for inclusion in a database */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $accessionNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string pageString Used for full display of pagination */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $pageString = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string firstPage Used for isolated representation of first page */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $firstPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string lastPage Used for isolated representation of last page */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $lastPage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string pageCount Number of pages or screens */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $pageCount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown copyright Copyright notice for the full article or artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown $copyright = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
