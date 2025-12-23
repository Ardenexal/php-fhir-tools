<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact
 * @description The article or artifact being described.
 */
class FHIRCitationCitedArtifact extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier May include DOI, PMID, PMCID, etc. */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> relatedIdentifier May include trial registry identifiers */
		public array $relatedIdentifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime dateAccessed When the cited artifact was accessed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $dateAccessed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactVersion version The defined version of the cited artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactVersion $version = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> currentState The status of the cited artifact */
		public array $currentState = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactStatusDate> statusDate An effective date or period for a status of the cited artifact */
		public array $statusDate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactTitle> title The title details of the article or artifact */
		public array $title = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactAbstract> abstract Summary of the article or artifact */
		public array $abstract = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPart part The component of the article or artifact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPart $part = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactRelatesTo> relatesTo The artifact related to the cited artifact */
		public array $relatesTo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactPublicationForm> publicationForm If multiple, used to represent alternative forms of the article that are not separate citations */
		public array $publicationForm = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactWebLocation> webLocation Used for any URL for the article or artifact cited */
		public array $webLocation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactClassification> classification The assignment to an organizing scheme */
		public array $classification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorship contributorship Attribution of authors and other contributors */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorship $contributorship = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Any additional information or content for the article or artifact */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
