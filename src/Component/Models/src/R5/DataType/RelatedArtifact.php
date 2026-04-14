<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 *
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R5')]
class RelatedArtifact extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var RelatedArtifactTypeType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?RelatedArtifactTypeType $type = null,
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
        /** @var PublicationStatusType|null publicationStatus draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?PublicationStatusType $publicationStatus = null,
        /** @var DatePrimitive|null publicationDate Date of publication of the artifact being referred to */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $publicationDate = null,
    ) {
        parent::__construct($id, $extension);
    }
}
