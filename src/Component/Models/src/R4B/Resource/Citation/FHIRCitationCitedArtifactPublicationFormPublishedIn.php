<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description The collection the cited article or artifact is published in.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm.publishedIn', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactPublicationFormPublishedIn extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Kind of container (e.g. Periodical, database, or book) */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRIdentifier> identifier Journal identifiers include ISSN, ISO Abbreviation and NLMuniqueID; Book identifiers include ISBN */
        public array $identifier = [],
        /** @var FHIRString|string|null title Name of the database or title of the book or journal */
        public FHIRString|string|null $title = null,
        /** @var FHIRReference|null publisher Name of the publisher */
        public ?FHIRReference $publisher = null,
        /** @var FHIRString|string|null publisherLocation Geographic location of the publisher */
        public FHIRString|string|null $publisherLocation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
