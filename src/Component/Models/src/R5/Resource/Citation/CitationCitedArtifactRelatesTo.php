<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Citation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifactTypeExpandedType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The artifact related to the cited artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.relatesTo', fhirVersion: 'R5')]
class CitationCitedArtifactRelatesTo extends BackboneElement
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
        /** @var RelatedArtifactTypeExpandedType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as | reprint | reprint-of */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?RelatedArtifactTypeExpandedType $type = null,
        /** @var array<CodeableConcept> classifier Additional classifiers */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $classifier = [],
        /** @var StringPrimitive|string|null label Short label */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null display Brief description of the related artifact */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $display = null,
        /** @var MarkdownPrimitive|null citation Bibliographic citation for the artifact */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $citation = null,
        /** @var Attachment|null document What document is being referenced */
        #[FhirProperty(fhirType: 'Attachment', propertyKind: 'complex')]
        public ?Attachment $document = null,
        /** @var CanonicalPrimitive|null resource What artifact is being referenced */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $resource = null,
        /** @var Reference|null resourceReference What artifact, if not a conformance resource */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $resourceReference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
