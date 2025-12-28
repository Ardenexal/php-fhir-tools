<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactPublicationForm extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCitationCitedArtifactPublicationFormPublishedIn|null publishedIn The collection the cited article or artifact is published in */
        public ?FHIRCitationCitedArtifactPublicationFormPublishedIn $publishedIn = null,
        /** @var FHIRCitationCitedArtifactPublicationFormPeriodicRelease|null periodicRelease The specific issue in which the cited article resides */
        public ?FHIRCitationCitedArtifactPublicationFormPeriodicRelease $periodicRelease = null,
        /** @var FHIRDateTime|null articleDate The date the article was added to the database, or the date the article was released */
        public ?FHIRDateTime $articleDate = null,
        /** @var FHIRDateTime|null lastRevisionDate The date the article was last revised or updated in the database */
        public ?FHIRDateTime $lastRevisionDate = null,
        /** @var array<FHIRCodeableConcept> language Language in which this form of the article is published */
        public array $language = [],
        /** @var FHIRString|string|null accessionNumber Entry number or identifier for inclusion in a database */
        public FHIRString|string|null $accessionNumber = null,
        /** @var FHIRString|string|null pageString Used for full display of pagination */
        public FHIRString|string|null $pageString = null,
        /** @var FHIRString|string|null firstPage Used for isolated representation of first page */
        public FHIRString|string|null $firstPage = null,
        /** @var FHIRString|string|null lastPage Used for isolated representation of last page */
        public FHIRString|string|null $lastPage = null,
        /** @var FHIRString|string|null pageCount Number of pages or screens */
        public FHIRString|string|null $pageCount = null,
        /** @var FHIRMarkdown|null copyright Copyright notice for the full article or artifact */
        public ?FHIRMarkdown $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
