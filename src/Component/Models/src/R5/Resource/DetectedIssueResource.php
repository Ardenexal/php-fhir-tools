<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DetectedIssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DetectedIssueStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\DetectedIssue\DetectedIssueEvidence;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\DetectedIssue\DetectedIssueMitigation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DetectedIssue
 *
 * @description Indicates an actual or potential clinical issue with or between one or more active or proposed clinical actions for a patient; e.g. Drug-drug interaction, Ineffective treatment frequency, Procedure-condition conflict, gaps in care, etc.
 */
#[FhirResource(type: 'DetectedIssue', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/DetectedIssue', fhirVersion: 'R5')]
class DetectedIssueResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique id for the detected issue */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var DetectedIssueStatusType|null status preliminary | final | entered-in-error | mitigated */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DetectedIssueStatusType $status = null,
        /** @var array<CodeableConcept> category Type of detected issue, e.g. drug-drug, duplicate therapy, etc */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var CodeableConcept|null code Specific type of detected issue, e.g. drug-drug, duplicate therapy, etc */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var DetectedIssueSeverityType|null severity high | moderate | low */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?DetectedIssueSeverityType $severity = null,
        /** @var Reference|null subject Associated subject */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter detected issue is part of */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|null identified When identified */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'identifiedDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'identifiedPeriod',
                ],
            ],
        )]
        public DateTimePrimitive|Period|null $identified = null,
        /** @var Reference|null author The provider or device that identified the issue */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $author = null,
        /** @var array<Reference> implicated Problem resource */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $implicated = [],
        /** @var array<DetectedIssueEvidence> evidence Supporting evidence */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\DetectedIssue\DetectedIssueEvidence',
        )]
        public array $evidence = [],
        /** @var MarkdownPrimitive|null detail Description and context */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $detail = null,
        /** @var UriPrimitive|null reference Authority for issue */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $reference = null,
        /** @var array<DetectedIssueMitigation> mitigation Step taken to address */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\DetectedIssue\DetectedIssueMitigation',
        )]
        public array $mitigation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
