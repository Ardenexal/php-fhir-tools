<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @description The article or artifact being described.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact', fhirVersion: 'R5')]
class CitationCitedArtifact extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique identifier. May include DOI, PMID, PMCID, etc */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        #[Valid]
        public array $identifier = [],
        /** @var array<Identifier> relatedIdentifier Identifier not unique to the cited artifact. May include trial registry identifiers */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        #[Valid]
        public array $relatedIdentifier = [],
        /** @var DateTimePrimitive|null dateAccessed When the cited artifact was accessed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $dateAccessed = null,
        /** @var CitationCitedArtifactVersion|null version The defined version of the cited artifact */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone'), Valid]
        public ?CitationCitedArtifactVersion $version = null,
        /** @var array<CodeableConcept> currentState The status of the cited artifact */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[Valid]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/cited-artifact-status-type', strength: 'extensible')]
        public array $currentState = [],
        /** @var array<CitationCitedArtifactStatusDate> statusDate An effective date or period for a status of the cited artifact */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactStatusDate',
        )]
        #[Valid]
        public array $statusDate = [],
        /** @var array<CitationCitedArtifactTitle> title The title details of the article or artifact */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactTitle',
        )]
        #[Valid]
        public array $title = [],
        /** @var array<CitationCitedArtifactAbstract> abstract Summary of the article or artifact */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactAbstract',
        )]
        #[Valid]
        public array $abstract = [],
        /** @var CitationCitedArtifactPart|null part The component of the article or artifact */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone'), Valid]
        public ?CitationCitedArtifactPart $part = null,
        /** @var array<CitationCitedArtifactRelatesTo> relatesTo The artifact related to the cited artifact */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactRelatesTo',
        )]
        #[Valid]
        public array $relatesTo = [],
        /** @var array<CitationCitedArtifactPublicationForm> publicationForm If multiple, used to represent alternative forms of the article that are not separate citations */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactPublicationForm',
        )]
        #[Valid]
        public array $publicationForm = [],
        /** @var array<CitationCitedArtifactWebLocation> webLocation Used for any URL for the article or artifact cited */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactWebLocation',
        )]
        #[Valid]
        public array $webLocation = [],
        /** @var array<CitationCitedArtifactClassification> classification The assignment to an organizing scheme */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation\CitationCitedArtifactClassification',
        )]
        #[Valid]
        public array $classification = [],
        /** @var CitationCitedArtifactContributorship|null contributorship Attribution of authors and other contributors */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone'), Valid]
        public ?CitationCitedArtifactContributorship $contributorship = null,
        /** @var array<Annotation> note Any additional information or content for the article or artifact */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        #[Valid]
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
