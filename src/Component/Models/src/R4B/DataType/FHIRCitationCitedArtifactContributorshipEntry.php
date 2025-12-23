<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.contributorship.entry
 * @description An individual entity named in the author list or contributor list.
 */
class FHIRCitationCitedArtifactContributorshipEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRHumanName name A name associated with the person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRHumanName $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string initials Initials for forename */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $initials = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string collectiveName Used for collective or corporate name as an author */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $collectiveName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Author identifier, eg ORCID */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo> affiliationInfo Organizational affiliation */
		public array $affiliationInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress> address Physical mailing address */
		public array $address = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactPoint> telecom Email or telephone contact methods for the author or contributor */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> contributionType The specific contribution */
		public array $contributionType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept role The role of the contributor (e.g. author, editor, reviewer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
		public array $contributionInstance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean correspondingContact Indication of which contributor is the corresponding contributor for the role */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $correspondingContact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt listOrder Used to code order of authors */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt $listOrder = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
