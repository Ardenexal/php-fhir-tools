<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Organization affiliated with the entity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'Citation',
	elementPath: 'Citation.citedArtifact.contributorship.entry.affiliationInfo',
	fhirVersion: 'R4B',
)]
class FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string affiliation Display for the organization */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $affiliation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string role Role within the organization, such as professional title */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier Identifier for the organization */
		public array $identifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
