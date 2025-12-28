<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;

/**
 * @description This element is used to list authors and other contributors, their contact information, specific contributions, and summary statements.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactContributorship extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null complete Indicates if the list includes all authors and/or contributors */
        public ?FHIRBoolean $complete = null,
        /** @var array<FHIRCitationCitedArtifactContributorshipEntry> entry An individual entity named in the list */
        public array $entry = [],
        /** @var array<FHIRCitationCitedArtifactContributorshipSummary> summary Used to record a display of the author/contributor list without separate coding for each list member */
        public array $summary = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
