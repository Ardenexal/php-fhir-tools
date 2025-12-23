<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.publicationForm.periodicRelease.dateOfPublication
 * @description Defining the date on which the issue of the journal was published.
 */
class FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate date Date on which the issue of the journal was published */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string year Year on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $year = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string month Month on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $month = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string day Day on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $day = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string season Season on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $season = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string text Text representation of the date of which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
