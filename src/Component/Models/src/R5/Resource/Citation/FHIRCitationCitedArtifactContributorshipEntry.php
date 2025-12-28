<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description An individual entity named as a contributor, for example in the author list or contributor list.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.entry', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactContributorshipEntry extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference contributor The identity of the individual contributor */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $contributor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string forenameInitials For citation styles that use initials */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $forenameInitials = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> affiliation Organizational affiliation */
		public array $affiliation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> contributionType The specific contribution */
		public array $contributionType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept role The role of the contributor (e.g. author, editor, reviewer, funder) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
		public array $contributionInstance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean correspondingContact Whether the contributor is the corresponding contributor for the role */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $correspondingContact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt rankingOrder Ranked order of contribution */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $rankingOrder = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
