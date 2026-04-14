<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description If multiple, used to represent alternative forms of the article that are not separate citations.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.publicationForm', fhirVersion: 'R5')]
class CitationCitedArtifactPublicationForm extends BackboneElement
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
        /** @var CitationCitedArtifactPublicationFormPublishedIn|null publishedIn The collection the cited article or artifact is published in */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?CitationCitedArtifactPublicationFormPublishedIn $publishedIn = null,
        /** @var CodeableConcept|null citedMedium Internet or Print */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $citedMedium = null,
        /** @var StringPrimitive|string|null volume Volume number of journal or other collection in which the article is published */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $volume = null,
        /** @var StringPrimitive|string|null issue Issue, part or supplement of journal or other collection in which the article is published */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $issue = null,
        /** @var DateTimePrimitive|null articleDate The date the article was added to the database, or the date the article was released */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $articleDate = null,
        /** @var StringPrimitive|string|null publicationDateText Text representation of the date on which the issue of the cited artifact was published */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publicationDateText = null,
        /** @var StringPrimitive|string|null publicationDateSeason Season in which the cited artifact was published */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publicationDateSeason = null,
        /** @var DateTimePrimitive|null lastRevisionDate The date the article was last revised or updated in the database */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $lastRevisionDate = null,
        /** @var array<CodeableConcept> language Language(s) in which this form of the article is published */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $language = [],
        /** @var StringPrimitive|string|null accessionNumber Entry number or identifier for inclusion in a database */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $accessionNumber = null,
        /** @var StringPrimitive|string|null pageString Used for full display of pagination */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $pageString = null,
        /** @var StringPrimitive|string|null firstPage Used for isolated representation of first page */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $firstPage = null,
        /** @var StringPrimitive|string|null lastPage Used for isolated representation of last page */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $lastPage = null,
        /** @var StringPrimitive|string|null pageCount Number of pages or screens */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $pageCount = null,
        /** @var MarkdownPrimitive|null copyright Copyright notice for the full article or artifact */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
