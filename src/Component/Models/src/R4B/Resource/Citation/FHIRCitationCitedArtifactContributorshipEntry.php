<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description An individual entity named in the author list or contributor list.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.entry', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactContributorshipEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName name A name associated with the person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string initials Initials for forename */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $initials = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string collectiveName Used for collective or corporate name as an author */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $collectiveName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier Author identifier, eg ORCID */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo> affiliationInfo Organizational affiliation */
		public array $affiliationInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress> address Physical mailing address */
		public array $address = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint> telecom Email or telephone contact methods for the author or contributor */
		public array $telecom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> contributionType The specific contribution */
		public array $contributionType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept role The role of the contributor (e.g. author, editor, reviewer) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
		public array $contributionInstance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean correspondingContact Indication of which contributor is the corresponding contributor for the role */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $correspondingContact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt listOrder Used to code order of authors */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $listOrder = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
