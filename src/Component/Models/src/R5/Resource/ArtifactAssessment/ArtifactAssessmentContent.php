<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ArtifactAssessment;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ArtifactAssessmentInformationTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @description A component comment, classifier, or rating of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'ArtifactAssessment', elementPath: 'ArtifactAssessment.content', fhirVersion: 'R5')]
class ArtifactAssessmentContent extends BackboneElement
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
        /** @var ArtifactAssessmentInformationTypeType|null informationType comment | classifier | rating | container | response | change-request */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ArtifactAssessmentInformationTypeType $informationType = null,
        /** @var MarkdownPrimitive|null summary Brief summary of the content */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $summary = null,
        /** @var CodeableConcept|null type What type of content */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> classifier Rating, classifier, or assessment */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $classifier = [],
        /** @var Quantity|null quantity Quantitative rating */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var Reference|null author Who authored the content */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $author = null,
        /** @var array<UriPrimitive> path What the comment is directed to */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $path = [],
        /** @var array<RelatedArtifact> relatedArtifact Additional information */
        #[FhirProperty(
            fhirType: 'RelatedArtifact',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact',
        )]
        public array $relatedArtifact = [],
        /** @var bool|null freeToShare Acceptable to publicly share the resource content */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $freeToShare = null,
        /** @var array<ArtifactAssessmentContent> component Contained content */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ArtifactAssessment\ArtifactAssessmentContent',
        )]
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
