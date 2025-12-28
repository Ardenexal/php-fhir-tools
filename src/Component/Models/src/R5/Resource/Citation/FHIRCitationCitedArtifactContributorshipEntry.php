<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An individual entity named as a contributor, for example in the author list or contributor list.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.entry', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactContributorshipEntry extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null contributor The identity of the individual contributor */
        #[NotBlank]
        public ?FHIRReference $contributor = null,
        /** @var FHIRString|string|null forenameInitials For citation styles that use initials */
        public FHIRString|string|null $forenameInitials = null,
        /** @var array<FHIRReference> affiliation Organizational affiliation */
        public array $affiliation = [],
        /** @var array<FHIRCodeableConcept> contributionType The specific contribution */
        public array $contributionType = [],
        /** @var FHIRCodeableConcept|null role The role of the contributor (e.g. author, editor, reviewer, funder) */
        public ?FHIRCodeableConcept $role = null,
        /** @var array<FHIRCitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
        public array $contributionInstance = [],
        /** @var FHIRBoolean|null correspondingContact Whether the contributor is the corresponding contributor for the role */
        public ?FHIRBoolean $correspondingContact = null,
        /** @var FHIRPositiveInt|null rankingOrder Ranked order of contribution */
        public ?FHIRPositiveInt $rankingOrder = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
