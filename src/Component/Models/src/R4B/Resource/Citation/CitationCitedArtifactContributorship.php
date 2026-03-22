<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description This element is used to list authors and other contributors, their contact information, specific contributions, and summary statements.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship', fhirVersion: 'R4B')]
class CitationCitedArtifactContributorship extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var bool|null complete Indicates if the list includes all authors and/or contributors */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $complete = null,
        /** @var array<CitationCitedArtifactContributorshipEntry> entry An individual entity named in the list */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation\CitationCitedArtifactContributorshipEntry',
        )]
        public array $entry = [],
        /** @var array<CitationCitedArtifactContributorshipSummary> summary Used to record a display of the author/contributor list without separate coding for each list member */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation\CitationCitedArtifactContributorshipSummary',
        )]
        public array $summary = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
