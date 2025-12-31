<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description This element is used to list authors and other contributors, their contact information, specific contributions, and summary statements.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactContributorship extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean complete Indicates if the list includes all authors and/or contributors */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $complete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCitationCitedArtifactContributorshipEntry> entry An individual entity named as a contributor */
		public array $entry = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCitationCitedArtifactContributorshipSummary> summary Used to record a display of the author/contributor list without separate data element for each list member */
		public array $summary = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
