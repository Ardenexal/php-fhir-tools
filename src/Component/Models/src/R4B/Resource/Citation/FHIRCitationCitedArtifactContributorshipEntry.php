<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description An individual entity named in the author list or contributor list.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.entry', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactContributorshipEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRHumanName|null name A name associated with the person */
        public ?\FHIRHumanName $name = null,
        /** @var FHIRString|string|null initials Initials for forename */
        public \FHIRString|string|null $initials = null,
        /** @var FHIRString|string|null collectiveName Used for collective or corporate name as an author */
        public \FHIRString|string|null $collectiveName = null,
        /** @var array<FHIRIdentifier> identifier Author identifier, eg ORCID */
        public array $identifier = [],
        /** @var array<FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo> affiliationInfo Organizational affiliation */
        public array $affiliationInfo = [],
        /** @var array<FHIRAddress> address Physical mailing address */
        public array $address = [],
        /** @var array<FHIRContactPoint> telecom Email or telephone contact methods for the author or contributor */
        public array $telecom = [],
        /** @var array<FHIRCodeableConcept> contributionType The specific contribution */
        public array $contributionType = [],
        /** @var FHIRCodeableConcept|null role The role of the contributor (e.g. author, editor, reviewer) */
        public ?\FHIRCodeableConcept $role = null,
        /** @var array<FHIRCitationCitedArtifactContributorshipEntryContributionInstance> contributionInstance Contributions with accounting for time or number */
        public array $contributionInstance = [],
        /** @var FHIRBoolean|null correspondingContact Indication of which contributor is the corresponding contributor for the role */
        public ?\FHIRBoolean $correspondingContact = null,
        /** @var FHIRPositiveInt|null listOrder Used to code order of authors */
        public ?\FHIRPositiveInt $listOrder = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
