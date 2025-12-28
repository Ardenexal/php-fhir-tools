<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The specific issue in which the cited article resides.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm.periodicRelease', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactPublicationFormPeriodicRelease extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null citedMedium Internet or Print */
        public ?\FHIRCodeableConcept $citedMedium = null,
        /** @var FHIRString|string|null volume Volume number of journal in which the article is published */
        public \FHIRString|string|null $volume = null,
        /** @var FHIRString|string|null issue Issue, part or supplement of journal in which the article is published */
        public \FHIRString|string|null $issue = null,
        /** @var FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication|null dateOfPublication Defining the date on which the issue of the journal was published */
        public ?\FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication $dateOfPublication = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
