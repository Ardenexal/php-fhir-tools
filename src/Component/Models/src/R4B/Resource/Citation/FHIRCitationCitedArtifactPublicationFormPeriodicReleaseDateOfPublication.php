<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Defining the date on which the issue of the journal was published.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'Citation',
	elementPath: 'Citation.citedArtifact.publicationForm.periodicRelease.dateOfPublication',
	fhirVersion: 'R4B',
)]
class FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate date Date on which the issue of the journal was published */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string year Year on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $year = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string month Month on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $month = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string day Day on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $day = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string season Season on which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $season = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string text Text representation of the date of which the issue of the journal was published */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
