<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.publicationForm.publishedIn
 * @description The collection the cited article or artifact is published in.
 */
class FHIRCitationCitedArtifactPublicationFormPublishedIn extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Kind of container (e.g. Periodical, database, or book) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Journal identifiers include ISSN, ISO Abbreviation and NLMuniqueID; Book identifiers include ISBN */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name of the database or title of the book or journal */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference publisher Name of or resource describing the publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $publisher = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publisherLocation Geographic location of the publisher */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $publisherLocation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
