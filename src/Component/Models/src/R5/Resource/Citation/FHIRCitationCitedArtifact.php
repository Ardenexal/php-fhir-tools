<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description The article or artifact being described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact', fhirVersion: 'R5')]
class FHIRCitationCitedArtifact extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique identifier. May include DOI, PMID, PMCID, etc */
        public array $identifier = [],
        /** @var array<FHIRIdentifier> relatedIdentifier Identifier not unique to the cited artifact. May include trial registry identifiers */
        public array $relatedIdentifier = [],
        /** @var FHIRDateTime|null dateAccessed When the cited artifact was accessed */
        public ?FHIRDateTime $dateAccessed = null,
        /** @var FHIRCitationCitedArtifactVersion|null version The defined version of the cited artifact */
        public ?FHIRCitationCitedArtifactVersion $version = null,
        /** @var array<FHIRCodeableConcept> currentState The status of the cited artifact */
        public array $currentState = [],
        /** @var array<FHIRCitationCitedArtifactStatusDate> statusDate An effective date or period for a status of the cited artifact */
        public array $statusDate = [],
        /** @var array<FHIRCitationCitedArtifactTitle> title The title details of the article or artifact */
        public array $title = [],
        /** @var array<FHIRCitationCitedArtifactAbstract> abstract Summary of the article or artifact */
        public array $abstract = [],
        /** @var FHIRCitationCitedArtifactPart|null part The component of the article or artifact */
        public ?FHIRCitationCitedArtifactPart $part = null,
        /** @var array<FHIRCitationCitedArtifactRelatesTo> relatesTo The artifact related to the cited artifact */
        public array $relatesTo = [],
        /** @var array<FHIRCitationCitedArtifactPublicationForm> publicationForm If multiple, used to represent alternative forms of the article that are not separate citations */
        public array $publicationForm = [],
        /** @var array<FHIRCitationCitedArtifactWebLocation> webLocation Used for any URL for the article or artifact cited */
        public array $webLocation = [],
        /** @var array<FHIRCitationCitedArtifactClassification> classification The assignment to an organizing scheme */
        public array $classification = [],
        /** @var FHIRCitationCitedArtifactContributorship|null contributorship Attribution of authors and other contributors */
        public ?FHIRCitationCitedArtifactContributorship $contributorship = null,
        /** @var array<FHIRAnnotation> note Any additional information or content for the article or artifact */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
