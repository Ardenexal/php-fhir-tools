<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Organization affiliated with the entity.
 */
#[FHIRBackboneElement(
    parentResource: 'Citation',
    elementPath: 'Citation.citedArtifact.contributorship.entry.affiliationInfo',
    fhirVersion: 'R4B',
)]
class FHIRCitationCitedArtifactContributorshipEntryAffiliationInfo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null affiliation Display for the organization */
        public \FHIRString|string|null $affiliation = null,
        /** @var FHIRString|string|null role Role within the organization, such as professional title */
        public \FHIRString|string|null $role = null,
        /** @var array<FHIRIdentifier> identifier Identifier for the organization */
        public array $identifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
