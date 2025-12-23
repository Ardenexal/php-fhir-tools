<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.contributorship.entry.affiliationInfo
 * @description Organization affiliated with the entity.
 */
class FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string affiliation Display for the organization */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $affiliation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string role Role within the organization, such as professional title */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Identifier for the organization */
		public array $identifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
