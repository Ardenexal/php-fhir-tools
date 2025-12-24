<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactPublicationForm extends FHIRBackboneElement
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
        /** @var FHIRCodeableConcept|null citedMedium Internet or Print */
        public ?FHIRCodeableConcept $citedMedium = null,
        /** @var FHIRString|string|null volume Volume number of journal or other collection in which the article is published */
        public FHIRString|string|null $volume = null,
        /** @var FHIRString|string|null issue Issue, part or supplement of journal or other collection in which the article is published */
        public FHIRString|string|null $issue = null,
        /** @var FHIRDateTime|null articleDate The date the article was added to the database, or the date the article was released */
        public ?FHIRDateTime $articleDate = null,
        /** @var FHIRString|string|null publicationDateText Text representation of the date on which the issue of the cited artifact was published */
        public FHIRString|string|null $publicationDateText = null,
        /** @var FHIRString|string|null publicationDateSeason Season in which the cited artifact was published */
        public FHIRString|string|null $publicationDateSeason = null,
        /** @var FHIRDateTime|null lastRevisionDate The date the article was last revised or updated in the database */
        public ?FHIRDateTime $lastRevisionDate = null,
        /** @var array<FHIRCodeableConcept> language Language(s) in which this form of the article is published */
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
