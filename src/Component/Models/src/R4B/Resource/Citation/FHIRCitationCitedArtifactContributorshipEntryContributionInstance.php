<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Contributions with accounting for time or number.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'Citation',
    elementPath: 'Citation.citedArtifact.contributorship.entry.contributionInstance',
    fhirVersion: 'R4B',
)]
class FHIRCitationCitedArtifactContributorshipEntryContributionInstance extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The specific contribution */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRDateTime|null time The time that the contribution was made */
        public ?FHIRDateTime $time = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
