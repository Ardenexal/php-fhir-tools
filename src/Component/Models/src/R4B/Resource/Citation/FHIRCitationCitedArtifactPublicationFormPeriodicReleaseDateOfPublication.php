<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Defining the date on which the issue of the journal was published.
 */
#[FHIRBackboneElement(
    parentResource: 'Citation',
    elementPath: 'Citation.citedArtifact.publicationForm.periodicRelease.dateOfPublication',
    fhirVersion: 'R4B',
)]
class FHIRCitationCitedArtifactPublicationFormPeriodicReleaseDateOfPublication extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDate|null date Date on which the issue of the journal was published */
        public ?FHIRDate $date = null,
        /** @var FHIRString|string|null year Year on which the issue of the journal was published */
        public FHIRString|string|null $year = null,
        /** @var FHIRString|string|null month Month on which the issue of the journal was published */
        public FHIRString|string|null $month = null,
        /** @var FHIRString|string|null day Day on which the issue of the journal was published */
        public FHIRString|string|null $day = null,
        /** @var FHIRString|string|null season Season on which the issue of the journal was published */
        public FHIRString|string|null $season = null,
        /** @var FHIRString|string|null text Text representation of the date of which the issue of the journal was published */
        public FHIRString|string|null $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
